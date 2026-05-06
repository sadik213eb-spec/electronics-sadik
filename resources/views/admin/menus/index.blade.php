<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Manage</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f3f4f6;
        }

        .admin-wrapper {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2a2a2a;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: 0.2s;
        }

        .btn-primary {
            background: #d97706;
            color: #fff;
        }

        .btn-primary:hover {
            background: #b45309;
        }

        .btn-danger {
            background: #dc2626;
            color: #fff;
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .btn-edit {
            background: #2563eb;
            color: #fff;
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .layout {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 24px;
            align-items: start;
        }

        /* FORM */
        .form-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #dddddd;
            position: sticky;
            top: 20px;
        }

        .form-title {
            font-size: 1rem;
            font-weight: 700;
            color: #2a2a2a;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #d97706;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #2a2a2a;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #d97706;
        }

        .location-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
        }

        .location-tab {
            flex: 1;
            padding: 10px;
            border: 1.5px solid #dddddd;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            color: #2a2a2a;
            transition: 0.2s;
            background: #fff;
        }

        .location-tab.active {
            background: #d97706;
            color: #fff;
            border-color: #d97706;
        }

        /* MENU SECTIONS */
        .menu-sections {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .menu-section {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #dddddd;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            background: #2a2a2a;
            padding: 10px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title.top {
            background: #16a34a;
        }

        .section-title.footer {
            background: #2a2a2a;
        }

        /* MENU ITEMS */
        .menu-list {
            min-height: 60px;
            border: 2px dashed #dddddd;
            border-radius: 8px;
            padding: 8px;
        }

        .menu-item {
            background: #f9fafb;
            border: 1px solid #dddddd;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 8px;
            cursor: grab;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .menu-item:active {
            cursor: grabbing;
        }

        .menu-item .drag-handle {
            color: #9ca3af;
            font-size: 1.1rem;
            cursor: grab;
        }

        .menu-item .item-info {
            flex: 1;
        }

        .menu-item .item-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2a2a2a;
        }

        .menu-item .item-url {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .menu-item .item-actions {
            display: flex;
            gap: 6px;
        }

        .children-list {
            margin-left: 30px;
            margin-top: 8px;
            min-height: 40px;
            border: 1.5px dashed #d97706;
            border-radius: 8px;
            padding: 6px;
        }

        .child-item {
            background: #fffbf0;
            border: 1px solid #fde68a;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 6px;
            cursor: grab;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            width: 400px;
            max-width: 90%;
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #d97706;
        }

        .sortable-ghost {
            opacity: 0.4;
            background: #fde68a;
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">
        <div class="page-header">
            <h1 class="page-title">Menu Manage</h1>
            <a href="/new-login/dashboard" class="btn btn-primary">← Back to Dashboard</a>
        </div>

        <div class="layout">
            {{-- FORM --}}
            <div class="form-card">
                <h3 class="form-title">Add New Menu Item</h3>

                <div class="location-tabs">
                    <button class="location-tab active" onclick="setLocation('top', this)">Top Menu</button>
                    <button class="location-tab" onclick="setLocation('footer', this)">Footer Menu</button>
                </div>

                <input type="hidden" id="form-location" value="top">

                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" id="menu-name" class="form-control" placeholder="e.g. Smartphones">
                </div>

                <div class="form-group">
                    <label class="form-label">URL *</label>
                    <input type="text" id="menu-url" class="form-control" placeholder="e.g. /category/smartphones">
                </div>

                <div class="form-group">
                    <label class="form-label">Image (optional)</label>
                    <input type="file" id="menu-image" class="form-control" accept="image/*">
                </div>

                <button class="btn btn-primary" style="width:100%" onclick="addMenu()">
                    + Add to Menu
                </button>
            </div>

            {{-- MENU SECTIONS --}}
            <div class="menu-sections">

                {{-- TOP MENU --}}
                <div class="menu-section">
                    <div class="section-title top">🔝 Top Menu</div>
                    <div class="menu-list" id="top-menu-list" data-location="top">
                        @foreach ($topMenus as $menu)
                            <div class="menu-item" data-id="{{ $menu->id }}" data-location="top">
                                <span class="drag-handle">⠿</span>
                                <div class="item-info">
                                    <p class="item-name">{{ $menu->name }}</p>
                                    <p class="item-url">{{ $menu->url }}</p>
                                </div>
                                <div class="item-actions">
                                    <button class="btn btn-edit"
                                        onclick="openEdit({{ $menu->id }}, '{{ $menu->name }}', '{{ $menu->url }}')">Edit</button>
                                    <button class="btn btn-danger"
                                        onclick="deleteMenu({{ $menu->id }}, this)">Delete</button>
                                </div>
                            </div>
                            @if ($menu->children->count())
                                <div class="children-list" data-parent="{{ $menu->id }}" data-location="top">
                                    @foreach ($menu->children as $child)
                                        <div class="child-item menu-item" data-id="{{ $child->id }}"
                                            data-location="top">
                                            <span class="drag-handle">⠿</span>
                                            <div class="item-info">
                                                <p class="item-name">{{ $child->name }}</p>
                                                <p class="item-url">{{ $child->url }}</p>
                                            </div>
                                            <div class="item-actions">
                                                <button class="btn btn-edit"
                                                    onclick="openEdit({{ $child->id }}, '{{ $child->name }}', '{{ $child->url }}')">Edit</button>
                                                <button class="btn btn-danger"
                                                    onclick="deleteMenu({{ $child->id }}, this)">Delete</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="children-list" data-parent="{{ $menu->id }}" data-location="top"></div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- FOOTER MENU --}}
                <div class="menu-section">
                    <div class="section-title footer">🔻 Footer Menu</div>
                    <div class="menu-list" id="footer-menu-list" data-location="footer">
                        @foreach ($footerMenus as $menu)
                            <div class="menu-item" data-id="{{ $menu->id }}" data-location="footer">
                                <span class="drag-handle">⠿</span>
                                <div class="item-info">
                                    <p class="item-name">{{ $menu->name }}</p>
                                    <p class="item-url">{{ $menu->url }}</p>
                                </div>
                                <div class="item-actions">
                                    <button class="btn btn-edit"
                                        onclick="openEdit({{ $menu->id }}, '{{ $menu->name }}', '{{ $menu->url }}')">Edit</button>
                                    <button class="btn btn-danger"
                                        onclick="deleteMenu({{ $menu->id }}, this)">Delete</button>
                                </div>
                            </div>
                            @if ($menu->children->count())
                                <div class="children-list" data-parent="{{ $menu->id }}" data-location="footer">
                                    @foreach ($menu->children as $child)
                                        <div class="child-item menu-item" data-id="{{ $child->id }}"
                                            data-location="footer">
                                            <span class="drag-handle">⠿</span>
                                            <div class="item-info">
                                                <p class="item-name">{{ $child->name }}</p>
                                                <p class="item-url">{{ $child->url }}</p>
                                            </div>
                                            <div class="item-actions">
                                                <button class="btn btn-edit"
                                                    onclick="openEdit({{ $child->id }}, '{{ $child->name }}', '{{ $child->url }}')">Edit</button>
                                                <button class="btn btn-danger"
                                                    onclick="deleteMenu({{ $child->id }}, this)">Delete</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="children-list" data-parent="{{ $menu->id }}" data-location="footer">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal-overlay" id="edit-modal">
        <div class="modal">
            <h3 class="modal-title">Edit Menu Item</h3>
            <input type="hidden" id="edit-id">
            <div class="form-group">
                <label class="form-label">Name *</label>
                <input type="text" id="edit-name" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">URL *</label>
                <input type="text" id="edit-url" class="form-control">
            </div>
            <div style="display:flex; gap:10px; margin-top:16px;">
                <button class="btn btn-primary" style="flex:1" onclick="saveEdit()">Save</button>
                <button class="btn" style="flex:1; background:#f3f4f6;" onclick="closeEdit()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Location tabs
        function setLocation(location, btn) {
            document.getElementById('form-location').value = location;
            document.querySelectorAll('.location-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
        }

        // Add menu
        function addMenu() {
            const name = document.getElementById('menu-name').value.trim();
            const url = document.getElementById('menu-url').value.trim();
            const location = document.getElementById('form-location').value;
            const image = document.getElementById('menu-image').files[0];

            if (!name || !url) {
                alert('Name and URL are required!');
                return;
            }

            const formData = new FormData();
            formData.append('name', name);
            formData.append('url', url);
            formData.append('location', location);
            if (image) formData.append('image', image);

            fetch('/new-login/menu-manage', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) location.reload ? window.location.reload() : window.location.reload();
                });
        }

        // Delete menu
        function deleteMenu(id, btn) {
            if (!confirm('Delete this menu item?')) return;
            fetch(`/new-login/menu-manage/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) btn.closest('.menu-item').remove();
                });
        }

        // Edit modal
        function openEdit(id, name, url) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-url').value = url;
            document.getElementById('edit-modal').classList.add('active');
        }

        function closeEdit() {
            document.getElementById('edit-modal').classList.remove('active');
        }

        function saveEdit() {
            const id = document.getElementById('edit-id').value;
            const name = document.getElementById('edit-name').value.trim();
            const url = document.getElementById('edit-url').value.trim();

            if (!name || !url) {
                alert('Name and URL are required!');
                return;
            }

            const formData = new FormData();
            formData.append('name', name);
            formData.append('url', url);
            formData.append('_method', 'PUT');

            fetch(`/new-login/menu-manage/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) window.location.reload();
                });
        }

        // Drag & Drop with SortableJS
        function initSortable() {
            // Top menu parent list
            new Sortable(document.getElementById('top-menu-list'), {
                group: 'top',
                animation: 150,
                ghostClass: 'sortable-ghost',
                handle: '.drag-handle',
                onEnd: saveOrder
            });

            // Footer menu parent list
            new Sortable(document.getElementById('footer-menu-list'), {
                group: 'footer',
                animation: 150,
                ghostClass: 'sortable-ghost',
                handle: '.drag-handle',
                onEnd: saveOrder
            });

            // Children lists
            document.querySelectorAll('.children-list').forEach(list => {
                new Sortable(list, {
                    group: {
                        name: 'children',
                        pull: true,
                        put: true
                    },
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    handle: '.drag-handle',
                    onEnd: saveOrder
                });
            });
        }

        function saveOrder() {
            const items = [];

            // Top menu
            document.querySelectorAll('#top-menu-list > .menu-item').forEach((el, index) => {
                items.push({
                    id: el.dataset.id,
                    parent_id: null,
                    location: 'top',
                    order: index
                });

                const childList = el.nextElementSibling;
                if (childList && childList.classList.contains('children-list')) {
                    childList.querySelectorAll('.menu-item').forEach((child, ci) => {
                        items.push({
                            id: child.dataset.id,
                            parent_id: el.dataset.id,
                            location: 'top',
                            order: ci
                        });
                    });
                }
            });

            // Footer menu
            document.querySelectorAll('#footer-menu-list > .menu-item').forEach((el, index) => {
                items.push({
                    id: el.dataset.id,
                    parent_id: null,
                    location: 'footer',
                    order: index
                });

                const childList = el.nextElementSibling;
                if (childList && childList.classList.contains('children-list')) {
                    childList.querySelectorAll('.menu-item').forEach((child, ci) => {
                        items.push({
                            id: child.dataset.id,
                            parent_id: el.dataset.id,
                            location: 'footer',
                            order: ci
                        });
                    });
                }
            });

            fetch('/new-login/menu-manage/reorder', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    items
                })
            });
        }

        initSortable();
    </script>
</body>

</html>
