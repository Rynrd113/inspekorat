# Sistem Komponen Admin yang Konsisten

## 📋 Daftar Komponen

### 1. Layout & Structure
- `<x-admin.layout>` - Layout utama untuk semua halaman admin
- `<x-admin.page-header>` - Header halaman dengan title dan breadcrumbs
- `<x-admin.card>` - Card container untuk konten

### 2. Form Components
- `<x-admin.input>` - Input field (text, email, password, textarea, file, dll)
- `<x-admin.select>` - Select dropdown
- `<x-admin.button>` - Button dengan berbagai variant
- `<x-admin.form-actions>` - Actions untuk form (submit, cancel, dll)

### 3. Data Display
- `<x-admin.data-table>` - Table untuk menampilkan data
- `<x-admin.stats-card>` - Card untuk statistik
- `<x-admin.search-filter>` - Filter dan pencarian

### 4. UI Elements
- `<x-admin.modal>` - Modal dialog
- `<x-admin.alert>` - Alert notifications
- `<x-admin.badge>` - Badge/label

## 🎨 Panduan Penggunaan

### Layout Dasar
```blade
@extends('layouts.admin')

@section('content')
<x-admin.layout 
    title="Judul Halaman"
    :breadcrumbs="[
        ['label' => 'Parent', 'url' => route('admin.parent.index')],
        ['label' => 'Current']
    ]"
    description="Deskripsi halaman"
>
    <x-slot name="actions">
        <x-admin.button href="{{ route('admin.create') }}" variant="primary">
            Tambah Data
        </x-admin.button>
    </x-slot>

    {{-- Konten halaman --}}
</x-admin.layout>
@endsection
```

### Halaman Index/Daftar
```blade
<x-admin.layout 
    title="Daftar Data"
    :show-stats="true"
    :stats="[
        ['title' => 'Total', 'value' => '100', 'icon' => 'fas fa-database', 'color' => 'blue']
    ]"
    :show-filters="true"
    :filters="[
        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['aktif' => 'Aktif']]
    ]"
>
    <x-admin.data-table 
        :columns="[
            ['key' => 'nama', 'label' => 'Nama'],
            ['key' => 'status', 'label' => 'Status']
        ]"
        :rows="$data"
        :actions="[
            ['type' => 'link', 'label' => 'Edit', 'url' => fn($item) => route('admin.edit', $item)]
        ]"
    />
</x-admin.layout>
```

### Halaman Form
```blade
<x-admin.layout title="Form Data">
    <x-admin.card title="Form Input">
        <form method="POST" action="{{ route('admin.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-admin.input 
                    name="nama"
                    label="Nama"
                    required
                    placeholder="Masukkan nama..."
                />
                
                <x-admin.select 
                    name="status"
                    label="Status"
                    :options="['aktif' => 'Aktif', 'tidak_aktif' => 'Tidak Aktif']"
                    required
                />
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <x-admin.button variant="secondary">Batal</x-admin.button>
                <x-admin.button type="submit" variant="primary">Simpan</x-admin.button>
            </div>
        </form>
    </x-admin.card>
</x-admin.layout>
```

## 🎯 Standar Konsistensi

### 1. Warna dan Styling
- **Primary**: Blue (`bg-blue-600`, `text-blue-600`)
- **Secondary**: Gray (`bg-gray-200`, `text-gray-900`)
- **Success**: Green (`bg-green-600`, `text-green-600`)
- **Danger**: Red (`bg-red-600`, `text-red-600`)
- **Warning**: Yellow (`bg-yellow-600`, `text-yellow-600`)

### 2. Spacing
- **Card padding**: `p-6`
- **Form spacing**: `space-y-6`
- **Button spacing**: `space-x-3`
- **Grid gaps**: `gap-6`

### 3. Typography
- **Page title**: `text-2xl font-bold text-gray-900`
- **Section title**: `text-lg font-semibold text-gray-900`
- **Label**: `text-sm font-medium text-gray-700`
- **Description**: `text-sm text-gray-600`

### 4. Icons
- **Font Awesome**: Gunakan konsisten
- **Size**: `text-xl` untuk icon besar, `text-sm` untuk icon kecil
- **Spacing**: `mr-2` untuk icon di kiri, `ml-2` untuk icon di kanan

### 5. Responsivitas
- **Mobile first**: Gunakan classes seperti `md:grid-cols-2`
- **Breakpoints**: `sm:`, `md:`, `lg:`, `xl:`
- **Grid**: `grid-cols-1 md:grid-cols-2 lg:grid-cols-3`

## 📝 Template Files

Gunakan template files di `resources/views/admin/_templates/` sebagai panduan:
- `index.blade.php` - Halaman daftar data
- `create.blade.php` - Halaman tambah data
- `edit.blade.php` - Halaman edit data
- `show.blade.php` - Halaman detail data

## 🔧 Customization

### Button Variants
```blade
<x-admin.button variant="primary">Primary</x-admin.button>
<x-admin.button variant="secondary">Secondary</x-admin.button>
<x-admin.button variant="danger">Danger</x-admin.button>
<x-admin.button variant="success">Success</x-admin.button>
<x-admin.button variant="outline">Outline</x-admin.button>
```

### Input Types
```blade
<x-admin.input type="text" name="nama" label="Nama" />
<x-admin.input type="email" name="email" label="Email" />
<x-admin.input type="password" name="password" label="Password" />
<x-admin.input type="textarea" name="deskripsi" label="Deskripsi" />
<x-admin.input type="file" name="gambar" label="Gambar" />
```

### Card Variants
```blade
<x-admin.card title="Default Card">Content</x-admin.card>
<x-admin.card title="With Icon" icon="fas fa-user">Content</x-admin.card>
<x-admin.card variant="success">Success card</x-admin.card>
```

## 🚀 Best Practices

1. **Selalu gunakan komponen yang sudah ada** sebelum membuat yang baru
2. **Konsisten dengan naming convention** untuk props dan classes
3. **Gunakan grid system** untuk responsive layout
4. **Implementasikan proper error handling** dengan komponen alert
5. **Tambahkan loading states** untuk user experience yang baik
6. **Gunakan modal untuk confirmations** seperti delete actions
7. **Implementasikan breadcrumbs** untuk navigasi yang jelas
8. **Gunakan stats cards** untuk memberikan overview data
9. **Implementasikan search dan filter** untuk data yang banyak
10. **Gunakan pagination** untuk performance yang baik

## 📋 Checklist Implementasi

- [ ] Header dengan title dan breadcrumbs
- [ ] Actions button di header
- [ ] Stats cards (jika diperlukan)
- [ ] Search dan filter (jika diperlukan)
- [ ] Data table dengan proper actions
- [ ] Modal confirmations
- [ ] Proper error handling
- [ ] Loading states
- [ ] Responsive design
- [ ] Consistent styling
