<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Http\Requests\StoreTeamMemberRequest;
use App\Http\Requests\UpdateTeamMemberRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = TeamMember::query();

            // Check if category column exists before using it
            $hasCategoryColumn = Schema::hasColumn('team_members', 'category');
            
            if ($hasCategoryColumn) {
                // Apply category filter only if column exists
                if ($request->filled('category')) {
                    $query->where('category', $request->category);
                }
                
                // Get categories for filter
                $categories = TeamMember::select('category')
                    ->distinct()
                    ->whereNotNull('category')
                    ->where('category', '!=', '')
                    ->pluck('category');
            } else {
                // If no category column, set empty collection
                $categories = collect();
            }

            // Apply subcategory filter (position should always exist)
            if ($request->filled('subcategory')) {
                $query->where('position', $request->subcategory);
            }

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('position', 'LIKE', "%{$search}%");
                });
            }

            // Pagination with ordering
            if ($hasCategoryColumn) {
                $teamMembers = $query->orderBy('category')
                                    ->orderBy('order_num')
                                    ->orderBy('name')
                                    ->paginate(10);
            } else {
                $teamMembers = $query->orderBy('order_num')
                                    ->orderBy('name')
                                    ->paginate(10);
            }

            // Get subcategories (positions) based on category filter
            $subcategories = collect();
            if ($request->filled('category') && $hasCategoryColumn) {
                $subcategories = TeamMember::where('category', $request->category)
                    ->select('position')
                    ->distinct()
                    ->whereNotNull('position')
                    ->pluck('position');
            } elseif ($request->filled('search')) {
                // If searching, get all positions for filtered results
                $subcategories = TeamMember::select('position')
                    ->distinct()
                    ->whereNotNull('position')
                    ->pluck('position');
            }

            return view('admin.team.index', compact('teamMembers', 'categories', 'subcategories'));
            
        } catch (Exception $e) {
            return back()->with('error', 'Error loading team members: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $positionOptions = $this->getPositionOptions();
            $socialPlatforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];
            
            return view('admin.team.create', compact('positionOptions', 'socialPlatforms'));
            
        } catch (Exception $e) {
            return back()->with('error', 'Error loading create form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // Custom validation since Request classes might not exist yet
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'order_num' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social.facebook' => 'nullable|url',
            'social.twitter' => 'nullable|url',
            'social.instagram' => 'nullable|url',
            'social.linkedin' => 'nullable|url',
            'social.youtube' => 'nullable|url',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'position' => $request->position,
                'order_num' => $request->order_num,
                'is_active' => 1
            ];

            // Add category if column exists
            if (Schema::hasColumn('team_members', 'category')) {
                $data['category'] = $this->determineCategory($request->position);
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = 'team_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

                // Create directory if not exists
                $uploadPath = public_path('uploads/team-members');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }

                // Move uploaded file to public/uploads/team-members
                $image->move($uploadPath, $filename);
                $data['image'] = 'team-members/' . $filename;
            }

            // Handle social links
            $socialLinks = [];
            if ($request->has('social')) {
                foreach ($request->social as $platform => $url) {
                    if (!empty($url)) {
                        $socialLinks[$platform] = $url;
                    }
                }
            }
            
            // Add social links if column exists
            if (Schema::hasColumn('team_members', 'social_links')) {
                $data['social_links'] = json_encode($socialLinks);
            }

            TeamMember::create($data);

            DB::commit();

            return redirect()->route('admin.team.index')
                ->with('success', 'Team member added successfully!');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            // Delete uploaded image if exists
            if (isset($data['image']) && Storage::disk('public')->exists($data['image'])) {
                Storage::disk('public')->delete($data['image']);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error adding team member: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $team = TeamMember::findOrFail($id);
            return view('admin.team.show', compact('team'));
        } catch (Exception $e) {
            return back()->with('error', 'Team member not found.');
        }
    }

    public function edit($id)
    {
        try {
            $team = TeamMember::findOrFail($id);
            $positionOptions = $this->getPositionOptions();
            $socialPlatforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];
            
            return view('admin.team.edit', compact('team', 'positionOptions', 'socialPlatforms'));
            
        } catch (Exception $e) {
            return back()->with('error', 'Error loading edit form: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Custom validation
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'order_num' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social.facebook' => 'nullable|url',
            'social.twitter' => 'nullable|url',
            'social.instagram' => 'nullable|url',
            'social.linkedin' => 'nullable|url',
            'social.youtube' => 'nullable|url',
        ]);

        try {
            DB::beginTransaction();

            $team = TeamMember::findOrFail($id);
            
            $data = [
                'name' => $request->name,
                'position' => $request->position,
                'order_num' => $request->order_num,
            ];

            // Add category if column exists
            if (Schema::hasColumn('team_members', 'category')) {
                $data['category'] = $this->determineCategory($request->position);
            }

            // Add is_active if column exists
            if (Schema::hasColumn('team_members', 'is_active')) {
                $data['is_active'] = $request->has('is_active') ? 1 : 0;
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($team->image && file_exists(public_path('uploads/' . $team->image))) {
                    unlink(public_path('uploads/' . $team->image));
                }

                $image = $request->file('image');
                $filename = 'team_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

                // Create directory if not exists
                $uploadPath = public_path('uploads/team-members');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }

                // Move uploaded file to public/uploads/team-members
                $image->move($uploadPath, $filename);
                $data['image'] = 'team-members/' . $filename;
            }

            // Handle social links
            if (Schema::hasColumn('team_members', 'social_links')) {
                $socialLinks = [];
                if ($request->has('social')) {
                    foreach ($request->social as $platform => $url) {
                        if (!empty($url)) {
                            $socialLinks[$platform] = $url;
                        }
                    }
                }
                $data['social_links'] = json_encode($socialLinks);
            }

            $team->update($data);

            DB::commit();

            return redirect()->route('admin.team.index')
                ->with('success', 'Team member updated successfully!');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating team member: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $team = TeamMember::findOrFail($id);

            // Delete image
            if ($team->image && file_exists(public_path('uploads/' . $team->image))) {
                unlink(public_path('uploads/' . $team->image));
            }

            $team->delete();

            DB::commit();

            return redirect()->route('admin.team.index')
                ->with('success', 'Team member deleted successfully!');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error deleting team member: ' . $e->getMessage());
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            if (!Schema::hasColumn('team_members', 'is_active')) {
                return back()->with('error', 'Status update not supported - missing is_active column');
            }

            $team = TeamMember::findOrFail($id);
            $team->update(['is_active' => $status]);
            
            $message = $status ? 'Team member activated' : 'Team member deactivated';
            
            return redirect()->back()->with('success', $message);
            
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    // API method for getting subcategories based on category
    public function getSubcategories(Request $request)
    {
        try {
            if (!Schema::hasColumn('team_members', 'category')) {
                return response()->json([]);
            }

            $subcategories = TeamMember::where('category', $request->category)
                ->select('position')
                ->distinct()
                ->whereNotNull('position')
                ->pluck('position');

            return response()->json($subcategories);
            
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Helper method to determine category based on position
    private function determineCategory($position)
    {
        if ($position === 'Penasehat') {
            return 'Penasehat';
        } elseif (in_array($position, ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara'])) {
            return 'Pengurus Pusat';
        } elseif (strpos($position, 'Departemen') !== false || strpos($position, 'Biro') !== false || 
                  strpos($position, 'Pokja') !== false || strpos($position, 'Mutu') !== false) {
            return 'Unsur Staf';
        } elseif (strpos($position, 'Divisi') !== false) {
            return 'Unsur Pelaksana';
        }
        return '';
    }

    // Helper method to get position options
    private function getPositionOptions()
    {
        return [
            'Penasehat' => [
                'Penasehat'
            ],
            'Pengurus Pusat Kontrasia' => [
                'Ketua',
                'Wakil Ketua',
                'Sekretaris',
                'Bendahara'
            ],
            'Unsur Staf Pimpinan' => [
                'Departemen Bidang Traumatologi dan ATI',
                'Departemen Diklat, R & D',
                'Departemen Logistik dan Inventarisasi',
                'Departemen Bencana',
                'Departemen Etika dan Disiplin',
                'Biro Hukum',
                'Pokja ATLS Edisi 10',
                'Mutu Pendidikan'
            ],
            'Unsur Pelaksana' => [
                'Divisi Jawa Timur',
                'Divisi Jawa Tengah dan DIY',
                'Divisi Jawa Barat dan Jakarta',
                'Divisi Bali, NTB, NTT',
                'Divisi Sumatera',
                'Divisi Kalimantan, Sulawesi',
                'Divisi Papua, Maluku'
            ]
        ];
    }

    // Test method to check table structure
    public function checkTableStructure()
    {
        if (!config('app.debug')) {
            return response()->json(['error' => 'Not available in production'], 403);
        }

        try {
            $columns = Schema::getColumnListing('team_members');
            $hasCategory = Schema::hasColumn('team_members', 'category');
            $hasSocialLinks = Schema::hasColumn('team_members', 'social_links');
            $hasIsActive = Schema::hasColumn('team_members', 'is_active');

            return response()->json([
                'columns' => $columns,
                'has_category' => $hasCategory,
                'has_social_links' => $hasSocialLinks,
                'has_is_active' => $hasIsActive,
                'table_exists' => Schema::hasTable('team_members')
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}