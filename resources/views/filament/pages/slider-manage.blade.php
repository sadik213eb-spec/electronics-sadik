<x-filament-panels::page>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    {{-- BESIDE SLIDER IMAGES --}}
    <div style="background:#1c1c1c; border-radius:12px; padding:24px; margin-bottom:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="color:#fff; font-size:1.1rem; font-weight:600;">Beside slider images</h2>
            <button wire:click="updateBanners"
                style="background:#374151; color:#fff; padding:8px 20px; border-radius:8px; border:none; cursor:pointer;">
                Update
            </button>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

            {{-- TOP BANNER --}}
            <div>
                <p style="color:#9ca3af; margin-bottom:8px; font-size:0.85rem;">Top Image</p>
                <div id="top_preview">
                    @if ($top_image)
                        <img src="{{ asset('storage/' . $top_image) }}"
                            style="width:100%; height:200px; object-fit:cover; border-radius:8px; margin-bottom:10px;">
                    @else
                        <div
                            style="width:100%; height:200px; background:#2d2d2d; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:10px;">
                            <span style="color:#6b7280;">No Image</span>
                        </div>
                    @endif
                </div>
                <input type="text" wire:model="top_link" placeholder="Link URL"
                    style="width:100%; background:#111; color:#fff; border:1px solid #374151; border-radius:6px; padding:8px 12px; margin-bottom:8px; font-size:0.85rem;">
                <div wire:ignore>
                    <select id="top_image_select" style="width:100%;">
                        <option value="">-- Search Image --</option>
                        @foreach (\App\Models\Media::all() as $media)
                            <option value="{{ $media->path }}" {{ $top_image == $media->path ? 'selected' : '' }}>
                                {{ $media->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- BOTTOM BANNER --}}
            <div>
                <p style="color:#9ca3af; margin-bottom:8px; font-size:0.85rem;">Bottom Image</p>
                <div id="bottom_preview">
                    @if ($bottom_image)
                        <img src="{{ asset('storage/' . $bottom_image) }}"
                            style="width:100%; height:200px; object-fit:cover; border-radius:8px; margin-bottom:10px;">
                    @else
                        <div
                            style="width:100%; height:200px; background:#2d2d2d; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:10px;">
                            <span style="color:#6b7280;">No Image</span>
                        </div>
                    @endif
                </div>
                <input type="text" wire:model="bottom_link" placeholder="Link URL"
                    style="width:100%; background:#111; color:#fff; border:1px solid #374151; border-radius:6px; padding:8px 12px; margin-bottom:8px; font-size:0.85rem;">
                <div wire:ignore>
                    <select id="bottom_image_select" style="width:100%;">
                        <option value="">-- Search Image --</option>
                        @foreach (\App\Models\Media::all() as $media)
                            <option value="{{ $media->path }}" {{ $bottom_image == $media->path ? 'selected' : '' }}>
                                {{ $media->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>

    {{-- SLIDER ITEMS TABLE --}}
    <div style="background:#1c1c1c; border-radius:12px; padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="color:#fff; font-size:1.1rem; font-weight:600;">Slider Items</h2>
            <button onclick="document.querySelector('.fi-btn').click()"
                style="background:#f59e0b; color:#000; padding:8px 18px; border-radius:8px; border:none; cursor:pointer; font-weight:600; font-size:0.9rem;">
                Add New Slider
            </button>
        </div>

        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1px solid #374151;">
                    <th style="color:#9ca3af; text-align:left; padding:10px; font-size:0.85rem;">Name</th>
                    <th style="color:#9ca3af; text-align:left; padding:10px; font-size:0.85rem;">Link</th>
                    <th style="color:#9ca3af; text-align:left; padding:10px; font-size:0.85rem;">Image</th>
                    <th style="color:#9ca3af; text-align:left; padding:10px; font-size:0.85rem;">Priority</th>
                    <th style="color:#9ca3af; text-align:left; padding:10px; font-size:0.85rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->getSliderItems() as $slider)
                    <tr style="border-bottom:1px solid #2d2d2d;">
                        <td style="padding:12px; color:#fff; font-size:0.9rem;">{{ $slider->name ?? '—' }}</td>
                        <td style="padding:12px; color:#9ca3af; font-size:0.85rem;">
                            {{ Str::limit($slider->link, 50) ?? '—' }}
                        </td>
                        <td style="padding:12px;">
                            @if ($slider->image)
                                <img src="{{ asset('storage/' . $slider->image) }}"
                                    style="width:80px; height:50px; object-fit:cover; border-radius:6px;">
                            @endif
                        </td>
                        <td style="padding:12px; color:#fff;">{{ $slider->order }}</td>
                        <td style="padding:12px;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="color:#9ca3af; font-size:0.8rem;">ON/OFF</span>
                                <button wire:click="toggleSlider({{ $slider->id }})"
                                    style="width:44px; height:24px; border-radius:12px; border:none; cursor:pointer; background:{{ $slider->is_active ? '#f59e0b' : '#374151' }}; transition:background 0.2s;">
                                </button>
                                <button wire:click="openEdit({{ $slider->id }})"
                                    style="background:#16a34a; color:#fff; padding:4px 10px; border-radius:6px; font-size:0.8rem; border:none; cursor:pointer;">✏️</button>
                                <button wire:click="deleteSlider({{ $slider->id }})" wire:confirm="Are you sure?"
                                    style="background:#dc2626; color:#fff; padding:4px 10px; border-radius:6px; font-size:0.8rem; border:none; cursor:pointer;">🗑️</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:24px; text-align:center; color:#6b7280;">No sliders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- EDIT MODAL --}}
    <div id="edit_modal"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#1c1c1c; border-radius:12px; padding:32px; width:500px; max-width:90%;">
            <h3 style="color:#fff; margin-bottom:20px; font-size:1.1rem;">Edit Slider</h3>

            <div style="margin-bottom:12px;">
                <label style="color:#9ca3af; font-size:0.85rem;">Name</label>
                <input type="text" wire:model="edit_name"
                    style="width:100%; background:#111; color:#fff; border:1px solid #374151; border-radius:6px; padding:8px 12px; margin-top:4px;">
            </div>

            <div style="margin-bottom:12px;">
                <label style="color:#9ca3af; font-size:0.85rem;">Link URL</label>
                <input type="text" wire:model="edit_link"
                    style="width:100%; background:#111; color:#fff; border:1px solid #374151; border-radius:6px; padding:8px 12px; margin-top:4px;">
            </div>

            <div style="margin-bottom:12px;">
                <label style="color:#9ca3af; font-size:0.85rem;">Priority</label>
                <input type="number" wire:model="edit_order"
                    style="width:100%; background:#111; color:#fff; border:1px solid #374151; border-radius:6px; padding:8px 12px; margin-top:4px;">
            </div>

            <div style="margin-bottom:20px;">
                <label style="color:#9ca3af; font-size:0.85rem; display:block; margin-bottom:4px;">Image</label>
                {{-- ✅ Plain select with wire:model — no TomSelect conflict --}}
                <select wire:model="edit_image"
                    style="width:100%; background:#111; color:#fff; border:1px solid #374151; border-radius:6px; padding:8px 12px; font-size:0.85rem;">
                    <option value="">-- Select Image --</option>
                    @foreach (\App\Models\Media::all() as $media)
                        <option value="{{ $media->path }}">{{ $media->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <button onclick="closeEditModal()"
                    style="background:#374151; color:#fff; padding:8px 20px; border-radius:8px; border:none; cursor:pointer;">
                    Cancel
                </button>
                <button wire:click="saveEdit"
                    style="background:#f59e0b; color:#000; padding:8px 20px; border-radius:8px; border:none; cursor:pointer; font-weight:600;">
                    Save
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const storageBase = '{{ asset('storage') }}/';

            // Top image TomSelect
            let topSelect = new TomSelect('#top_image_select', {
                placeholder: 'Search image...',
                onChange: function(value) {
                    @this.set('top_image', value);
                    const preview = document.getElementById('top_preview');
                    if (value) {
                        preview.innerHTML =
                            `<img src="${storageBase}${value}" style="width:100%; height:200px; object-fit:cover; border-radius:8px; margin-bottom:10px;">`;
                    } else {
                        preview.innerHTML =
                            `<div style="width:100%; height:200px; background:#2d2d2d; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:10px;"><span style="color:#6b7280;">No Image</span></div>`;
                    }
                }
            });

            // Bottom image TomSelect
            let bottomSelect = new TomSelect('#bottom_image_select', {
                placeholder: 'Search image...',
                onChange: function(value) {
                    @this.set('bottom_image', value);
                    const preview = document.getElementById('bottom_preview');
                    if (value) {
                        preview.innerHTML =
                            `<img src="${storageBase}${value}" style="width:100%; height:200px; object-fit:cover; border-radius:8px; margin-bottom:10px;">`;
                    } else {
                        preview.innerHTML =
                            `<div style="width:100%; height:200px; background:#2d2d2d; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:10px;"><span style="color:#6b7280;">No Image</span></div>`;
                    }
                }
            });

            // Open edit modal
            window.addEventListener('open-edit-modal', () => {
                document.getElementById('edit_modal').style.display = 'flex';
            });

            // Close edit modal
            window.addEventListener('close-edit-modal', () => {
                document.getElementById('edit_modal').style.display = 'none';
            });
        });

        function closeEditModal() {
            document.getElementById('edit_modal').style.display = 'none';
        }
    </script>

</x-filament-panels::page>
