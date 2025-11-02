<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Yeni Grup Oluştur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('groups.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="groups_name" :value="__('Grup Adı')" />
                            <x-text-input id="groups_name" class="block mt-1 w-full" type="text" name="groups_name" :value="old('groups_name')" required autofocus />
                            <x-input-error :messages="$errors->get('groups_name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="city_id" :value="__('Şehir')" />
                            <select name="city_id" id="city_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Şehir Seçiniz...</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="university_id" :value="__('Üniversite')" />
                            <select name="university_id" id="university_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required disabled>
                                <option value="">Önce Şehir Seçiniz...</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="faculty_id" :value="__('Fakülte')" />
                            <select name="faculty_id" id="faculty_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required disabled>
                                <option value="">Önce Üniversite Seçiniz...</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="department_id" :value="__('Bölüm')" />
                            <select name="department_id" id="department_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required disabled>
                                <option value="">Önce Fakülte Seçiniz...</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="class_models_id" :value="__('Sınıf')" />
                            <select name="class_models_id" id="class_models_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required disabled>
                                <option value="">Önce Bölüm Seçiniz...</option>
                            </select>
                            <x-input-error :messages="$errors->get('class_models_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Grubu Oluştur') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function fetchAndPopulate(url, targetElement, defaultOption) {
                // Önceki seçenekleri temizle ve "disabled" yap
                targetElement.innerHTML = `<option value="">${defaultOption}</option>`;
                targetElement.disabled = true;

                if (!url) return; // Eğer URL yoksa (örn: "Şehir Seçiniz" tıklandıysa)

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(item => {
                                targetElement.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                            });
                            targetElement.disabled = false; // Seçenek varsa aktifleştir
                        }
                    })
                    .catch(error => console.error('Hata:', error));
            }

            const citySelect = document.getElementById('city_id');
            const universitySelect = document.getElementById('university_id');
            const facultySelect = document.getElementById('faculty_id');
            const departmentSelect = document.getElementById('department_id');
            const classSelect = document.getElementById('class_models_id');

            citySelect.addEventListener('change', function () {
                const cityId = this.value;
                fetchAndPopulate(cityId ? `/api/universities/${cityId}` : null, universitySelect, 'Önce Üniversite Seçiniz...');
                // Alt seçimleri de sıfırla
                fetchAndPopulate(null, facultySelect, 'Önce Üniversite Seçiniz...');
                fetchAndPopulate(null, departmentSelect, 'Önce Fakülte Seçiniz...');
                fetchAndPopulate(null, classSelect, 'Önce Bölüm Seçiniz...');
            });

            universitySelect.addEventListener('change', function () {
                const universityId = this.value;
                fetchAndPopulate(universityId ? `/api/faculties/${universityId}` : null, facultySelect, 'Önce Fakülte Seçiniz...');
                fetchAndPopulate(null, departmentSelect, 'Önce Fakülte Seçiniz...');
                fetchAndPopulate(null, classSelect, 'Önce Bölüm Seçiniz...');
            });

            facultySelect.addEventListener('change', function () {
                const facultyId = this.value;
                fetchAndPopulate(facultyId ? `/api/departments/${facultyId}` : null, departmentSelect, 'Önce Bölüm Seçiniz...');
                fetchAndPopulate(null, classSelect, 'Önce Bölüm Seçiniz...');
            });

            departmentSelect.addEventListener('change', function () {
                const departmentId = this.value;
                fetchAndPopulate(departmentId ? `/api/classes/${departmentId}` : null, classSelect, 'Sınıf Seçiniz...');
            });
        });
    </script>
</x-app-layout>
