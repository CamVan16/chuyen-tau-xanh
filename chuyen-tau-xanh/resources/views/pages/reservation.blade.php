<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt chỗ</title>
    <style>
        .trains {
            display: flex;
        }
        .train {
            display: inline;
            width: 150px;
            height: 150px;
            border-radius: 10%;
        }
    </style>
</head>
<body>
    <?php
        $stationA = $_POST['stationA'];
        $stationB = $_POST['stationB'];
        $departureDate = $_POST['departureDate'];
        $ticketType = $_POST['ticketType'];
        $returnDate = isset($_POST['returnDate']) ? $_POST['returnDate'] : null;
    ?>
    <h3>Chiều đi: ngày {{$departureDate}} từ {{$stationA}} đến {{$stationB}}</h3>
    <div class="trains">
        @forelse($routes as $route)
            <div class="train">
                <h4>Tuyến {{ $route->train_mark }}</h4>
                <p>TG đi {{ $departureDate }} {{ $route->departure_time }}</p>
                <p>
                    TG đến 
                    <?php
                        $departureDateObj = new DateTime($departureDate);
                        $daysToAdd = $route->arrival_date_index - $route->departure_date_index;
                        $departureDateObj->modify("+$daysToAdd days");
                        echo $departureDateObj->format('Y-m-d');
                    ?>
                    {{ $route->arrival_time }}
                </p>
            </div>
        @empty
            <p>Không tìm thấy tuyến tàu phù hợp.</p>
        @endforelse
    </div>

    @if ($ticketType === 'round-trip')
    <h3>Chiều về: ngày {{$returnDate}} từ {{$stationB}} đến {{$stationA}}</h3>
        <div class="trains">
            @forelse($returnRoutes as $route)
                <div class="train">
                    <h4>Tuyến {{ $route->train_mark }}</h4>
                    <p>TG đi {{ $departureDate }} {{ $route->departure_time }}</p>
                    <p>
                        TG đến 
                        <?php
                            $departureDateObj = new DateTime($departureDate);
                            $daysToAdd = $route->arrival_date_index - $route->departure_date_index;
                            $departureDateObj->modify("+$daysToAdd days");
                            echo $departureDateObj->format('Y-m-d');
                        ?>
                        {{ $route->arrival_time }}
                    </p>
                </div>
            @empty
                <p>Không tìm thấy tuyến tàu phù hợp.</p>
            @endforelse
        </div>
    @endif
</body>
</html>