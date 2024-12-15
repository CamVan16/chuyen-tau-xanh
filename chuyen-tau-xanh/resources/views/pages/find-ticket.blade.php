@extends('layouts.app')

@section('title', 'Tìm vé')

@section('content')
<div>
    <form action="{{ route('routes.search') }}" method="POST">
        @csrf
        <label for="stationA">Ga Đi:</label>
        <div class="dropdown">
            <input type="text" name="stationA" id="stationA" class="form-control" autocomplete="off" required>
            <ul class="dropdown-menu" id="stationA-dropdown" style="width: 30%;"></ul>
        </div>

        <label for="stationB">Ga Đến:</label>
        <div class="dropdown">
            <input type="text" name="stationB" id="stationB" class="form-control" autocomplete="off" required>
            <ul class="dropdown-menu" id="stationB-dropdown" style="width: 30%;"></ul>
        </div>
        <!-- <label>Loại vé:</label> -->
        <label>
            <input type="radio" name="ticketType" value="one-way" checked onclick="toggleReturnDate()"> Một chiều
        </label>
        <label>
            <input type="radio" name="ticketType" value="round-trip" onclick="toggleReturnDate()"> Khứ hồi
        </label>

        <label for="departureDate">Ngày Đi:</label>
        <input type="date" name="departureDate" id="departureDate" required>

        <label for="returnDate">Ngày Về:</label>
        <input type="date" name="returnDate" id="returnDate" disabled>
        <br>
        <button type="submit">Tìm Kiếm</button>
    </form>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const stations = await fetch('{{ route('stations.name') }}')
                .then(response => response.json());
            // console.log(stations);
            setupAutocomplete('stationA', 'stationA-dropdown', stations);
            setupAutocomplete('stationB', 'stationB-dropdown', stations);
        });
        function setupAutocomplete(inputId, dropdownId, stations) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);

            input.addEventListener('input', function () {
                const query = input.value.trim().toLowerCase();
                // console.log(query);
                dropdown.innerHTML = '';
                if (query.length > 0) {
                    const filteredStations = stations.filter(station => station.name.toLowerCase().includes(query));
                    filteredStations.forEach(station => {
                        const li = document.createElement('li');
                        li.textContent = station.name;
                        li.classList.add('dropdown-item');
                        li.onclick = () => {
                            input.value = station.name;
                            dropdown.innerHTML = '';
                        };
                        dropdown.appendChild(li);
                    });
                    dropdown.style.display = 'block';
                } else {
                    dropdown.style.display = 'none';
                }
            });

            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target) && e.target !== input) {
                    dropdown.style.display = 'none';
                }
            });
        }
        function toggleReturnDate() {
            const returnDateInput = document.getElementById('returnDate');
            const isRoundTrip = document.querySelector('input[name="ticketType"]:checked').value === 'round-trip';
            returnDateInput.disabled = !isRoundTrip;
            returnDateInput.required = isRoundTrip;
        }
    </script>
    <style>
        label {
            display: block;
            margin-top: 10px;
        }
        .dropdown-menu {
            display: none;
            max-height: 200px;
            overflow-y: auto;
            position: absolute;
            z-index: 1000;
            background-color: #fff;
            border: 1px solid #ccc;
        }

        .dropdown-item {
            cursor: pointer;
            padding: 8px 12px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection