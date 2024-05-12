<!DOCTYPE html>
<html>
<head>
    <title>Flickr Photo Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #D3D3D3;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #36454F;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #7393B3;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            grid-gap: 20px;
        }
        .grid-item {
            position: relative;
            overflow: hidden;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .grid-item:hover {
            transform: translateY(-5px);
        }
        .grid-item img {
            width: 100%;
            height: auto;
            display: block;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .grid-item:hover .overlay {
            opacity: 1;
        }
        .overlay-content {
            text-align: center;
        }
        .overlay-content a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #36454F;
            transition: background-color 0.3s ease;
        }
        .overlay-content a:hover {
            background-color: #7393B3;
        }
        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px 0;
            background-color: #333;
            color: #fff;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 10px;
            margin: 0 5px;
            text-decoration: none;
            color: #fff;
            background-color: #36454F;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #7393B3;
        }
        .pagination .active {
            background-color: #808080;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Flickr Photo Search</h1>

    <form method="GET">
        <input type="text" name="search" placeholder="Search photos">
        <button type="submit">Search</button>
    </form>

    <div class="grid-container">
        <?php
        $apiKey = "71b1966355ddca4fec1345cac864a220"; 
        $perPage = 16;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $url = "https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=$apiKey&format=json&nojsoncallback=1&per_page=$perPage&text=$search&page=$page";
        $response = file_get_contents($url);
        $data = json_decode($response);

        foreach ($data->photos->photo as $p) {
            $farm = $p->farm;
            $server = $p->server;
            $id = $p->id;
            $secret = $p->secret;
            $title = $p->title;
            $imageUrl = "https://farm$farm.staticflickr.com/$server/{$id}_{$secret}_q.jpg";
            echo "<div class='grid-item'>
                      <img src='$imageUrl' alt='$title'>
                      <div class='overlay'>
                          <div class='overlay-content'>
                              <a href='$imageUrl' target='_blank'>View Full Screen</a>
                          </div>
                      </div>
                  </div>";
        }
        ?>
    </div>

    <div class="pagination">
        <?php

        $totalPages = ceil($data->photos->total / 16);

        $startPage = max(2, $page - 2);

        $endPage = min($startPage + 2, $totalPages);
        

        echo "<a href='?search=$search&page=1' " . (1 == $page ? "class='active'" : "") . ">1</a>";

        for ($i = $startPage; $i <= $endPage; $i++) {
            echo "<a href='?search=$search&page=$i' " . ($i == $page ? "class='active'" : "") . ">$i</a>";
        }
        echo "<a>...</a>";
        echo "<a href='?search=$search&page=$totalPages' " . ($totalPages == $page ? "class='active'" : "") . ">$totalPages</a>";

        ?>
    </div>
</div>

<footer>
    Flicker Images
</footer>

</body>
</html>
