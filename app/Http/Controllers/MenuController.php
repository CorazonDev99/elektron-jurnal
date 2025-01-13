<?php
namespace App\Http\Controllers;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $topMenus = Menu::where('parent_id', 0)->where('position', 'top')->with('children')->orderBy('ordering')->get();
        $sidebarMenus = Menu::where('parent_id', 0)->where('position', 'sidebar')->with('children')->orderBy('ordering')->get();
        $roles = Role::all();
        return view('core.menus.index', compact('topMenus', 'sidebarMenus', 'roles'));
    }


    public function store(Request $request)
    {
        $minOrder = Menu::min('ordering');

        $newOrder = $minOrder !== null ? $minOrder - 1 : 1;

        $menu = new Menu();
        $menu->menu_name = $request->input('menu_name');
        $menu->menu_type = $request->input('menu_type');
        $menu->url = $request->input('url');
        $menu->position = $request->input('position');
        $menu->menu_icons = $request->input('menu_icons');
        $menu->active = $request->input('active');
        $menu->access_data = json_encode($request->input('access'));
        $menu->ordering = $newOrder;
        $menu->parent_id = 0;

        $menu->save();

        return redirect()->route('menu.index');
    }

    public function updateOrder(Request $request)
    {
        \Log::info('Received menu order update: ', $request->input('menu'));

        try {
            \DB::transaction(function () use ($request) {
                $this->saveMenuOrder($request->input('menu'));
            });

            return response()->json(['status' => 'Order updated successfully']);
        } catch (\Exception $e) {
            \Log::error('Error updating menu order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update order'], 500);
        }
    }

    private function saveMenuOrder($menuItems, $parentId = 0)
    {
        foreach ($menuItems as $index => $menuItem) {
            \Log::info("Updating menu item {$menuItem['id']} with parent_id: {$parentId}, ordering: " . ($index + 1));

            $menu = Menu::find($menuItem['id']);

            if (!$menu) {
                throw new \Exception('Menu item not found with ID: ' . $menuItem['id']);
            }

            $menu->parent_id = $parentId;
            $menu->ordering = $index + 1;
            $menu->save();

            if (isset($menuItem['children']) && count($menuItem['children']) > 0) {
                $this->saveMenuOrder($menuItem['children'], $menu->menu_id);
            }
        }
    }
}

