@extends('layouts.app')

<style>
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: white;
    }

    .nav-tabs .nav-link {
        background-color: #f8f9fa;
        color: #007bff;
    }

    .menu-structure {
        list-style-type: none;
        padding-left: 0;
    }

    .menu-structure li {
        margin: 5px 0;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
        cursor: move;
        border: 1px solid #ced4da;
    }

    .menu-structure li ul {
        list-style-type: none;
        padding-left: 20px;
        margin-top: 10px;
    }

    .menu-structure li ul li {
        margin-bottom: 5px;
        padding: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }

    .menu-structure li:hover {
        background-color: #e2e6ea;
    }

</style>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Menu Structure</h2>
                <ul class="nav nav-tabs mb-3" id="menuTabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="topMenuTab" href="#">Верхнее меню</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="sidebarMenuTab" href="#">Боковое меню</a>
                    </li>
                </ul>

                <div id="topMenuSection" class="col-md-12">
                    <h2>Верхнее меню</h2>
                    <ul id="topMenuStructure" class="menu-structure">
                        @foreach ($topMenus  as $menu)
                            <li data-id="{{ $menu->menu_id }}" data-type="{{ $menu->menu_type }}">
                                <div class="menu-item">
                                    {{ $menu->menu_name }}
                                </div>
                                @if ($menu->children->count())
                                    <ul>
                                        @foreach ($menu->children as $child)
                                            <li data-id="{{ $child->menu_id }}" data-type="{{ $child->menu_type }}">
                                                <div class="menu-item">
                                                    {{ $child->menu_name }}
                                                </div>
                                                @if ($child->children->count())
                                                    <ul>
                                                        @foreach ($child->children as $subChild)
                                                            <li data-id="{{ $subChild->menu_id }}" data-type="{{ $subChild->menu_type }}">
                                                                <div class="menu-item">
                                                                    {{ $subChild->menu_name }}
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>


                <div id="sidebarMenuSection" class="col-md-12" style="display: none;">
                    <h2>Боковое меню</h2>
                    <ul id="sidebarMenuStructure" class="menu-structure">
                        @foreach ($sidebarMenus as $menu)
                            <li data-id="{{ $menu->menu_id }}" data-type="{{ $menu->menu_type }}">
                                <div class="menu-item">
                                    {{ $menu->menu_name }}
                                </div>
                                @if ($menu->children->count())
                                    <ul>
                                        @foreach ($menu->children as $child)
                                            <li data-id="{{ $child->menu_id }}" data-type="{{ $child->menu_type }}">
                                                <div class="menu-item">
                                                    {{ $child->menu_name }}
                                                </div>
                                                @if ($child->children->count())
                                                    <ul>
                                                        @foreach ($child->children as $subChild)
                                                            <li data-id="{{ $subChild->menu_id }}" data-type="{{ $subChild->menu_type }}">
                                                                <div class="menu-item">
                                                                    {{ $subChild->menu_name }}
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>



            <div class="col-md-6">
                <h3>Create New Menu</h3>
                <form action="{{ route('menu.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="menu_name" class="form-label">Название/Заголовок</label>
                        <input type="text" class="form-control" id="menu_name" name="menu_name" required>
                    </div>
                    <fieldset class="mb-3">
                        <label class="form-label">Тип меню</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="menu_type" id="internal" value="internal" checked>
                            <label class="form-check-label" for="internal">Внутренний</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="menu_type" id="external" value="external">
                            <label class="form-check-label" for="external">Внешний</label>
                        </div>
                    </fieldset>
                    <div class="mb-3">
                        <label for="url" class="form-label">Link</label>
                        <input type="text" class="form-control" id="url" name="url">
                    </div>
                    <fieldset class="mb-3">
                        <label class="form-label">Положение</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="position" id="top" value="top" checked>
                            <label class="form-check-label" for="top">Верхнее меню</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="position" id="sidebar" value="sidebar">
                            <label class="form-check-label" for="sidebar">Боковые меню</label>
                        </div>
                    </fieldset>
                    <div class="mb-3">
                        <label for="menu_icons" class="form-label">Иконка</label>
                        <input type="text" class="form-control" id="menu_icons" name="menu_icons" placeholder="fa fa-bars">
                    </div>
                    <fieldset class="mb-3">
                        <label class="form-label">Активный статус</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="active" id="active" value="1" checked>
                            <label class="form-check-label" for="active">Активный</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="active" id="inactive" value="0">
                            <label class="form-check-label" for="inactive">Неактивный</label>
                        </div>
                    </fieldset>
                    <div class="mb-3">
                        <label for="access" class="form-label">Доступ</label><br>
                        @foreach($roles as $role)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="access[]" value="{{ $role->id }}" id="role-{{ $role->id }}">
                                <label class="form-check-label" for="role-{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary">Создать</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        document.getElementById('topMenuTab').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('topMenuSection').style.display = 'block';
            document.getElementById('sidebarMenuSection').style.display = 'none';

            document.getElementById('topMenuTab').classList.add('active');
            document.getElementById('sidebarMenuTab').classList.remove('active');
        });

        document.getElementById('sidebarMenuTab').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('topMenuSection').style.display = 'none';
            document.getElementById('sidebarMenuSection').style.display = 'block';

            document.getElementById('topMenuTab').classList.remove('active');
            document.getElementById('sidebarMenuTab').classList.add('active');
        });

    </script>
    <script>
        function buildMenuHierarchy(container, parentId = 0) {
            let items = [];
            container.querySelectorAll(':scope > li').forEach((li, index) => {
                let item = {
                    id: li.dataset.id,
                    parent_id: parentId,
                    ordering: index + 1
                };

                // Проверяем наличие подменю (ul)
                let subMenu = li.querySelector('ul');
                if (subMenu && subMenu.children.length > 0) {
                    item.children = buildMenuHierarchy(subMenu, item.id);
                }

                items.push(item);
            });
            return items;
        }

        function applyNestedSortable(container) {
            new Sortable(container, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onEnd: function(evt) {
                    saveMenuOrder(evt.to);
                }
            });

            container.querySelectorAll('ul').forEach(function(subContainer) {
                applyNestedSortable(subContainer);
            });
        }

        function saveMenuOrder(container) {
            let menu = buildMenuHierarchy(document.getElementById('topMenuStructure'));
            console.log('Menu structure to be saved:', JSON.stringify(menu, null, 2));

            axios.post("{{ route('menu.updateOrder') }}", { menu: menu })
                .then(response => {
                    console.log('Menu order updated successfully', response.data);
                })
                .catch(error => {
                    console.error('Error updating menu order:', error.response.data);
                });
        }



        applyNestedSortable(document.getElementById('topMenuStructure'));
        applyNestedSortable(document.getElementById('sidebarMenuStructure'));
    </script>



@endsection
