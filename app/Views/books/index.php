<?php

$tableBody = "
    <div id='booksContainer'>
        <div id='nav'>
            <form method='post' action='/create'>
                <button type='submit' name='btn-plus' class='btn-plus' title='Új'>
                    <i class='fa fa-plus plus'></i>&nbsp;Új
                </button>
            </form>
            <form method='post' action='/'>
                <select class='btn-plus' id='listOptions'>
                    <option>címek</option>
                    <option>írók</option>
                    <option>kiadók</option>
                    <option>kategóriák</option>
                </select>
                <input type='hidden' name='_method' value='LIST'>
                <input type='hidden' name='table' value='books' id='table'>
                <input type='hidden' name='attribute' value='title' id='attribute'>
                <button type='submit' name='btn-list' class='btn-plus' title='Ok'>Listáz</button>
            </form>
            <form method='post' action='/'>
                <input type='hidden' name='_method' value='SEARCH'>
                <input type='hidden' name='searchByTable' id='searchByTable' value='books'>
                <input type='hidden' name='searchByAttribute' id='searchByAttribute' value='title'>
                <input type='text' name='text' placeholder='Az arany virágcserép' class='btn-plus'>
                <button type='submit' name='btn-search' class='btn-plus' title='search'><i class='fa-solid fa-magnifying-glass'></i></button>
            </form></div>";

foreach ($books as $book) {
    $rating = $book->getBookReview($book->id);
    $starAverage = 0;

    $reviewCount = count($rating);
    if ($reviewCount > 0){
        $starCount = 0;
        foreach ($rating as $star){
            $starCount += $star['rating'];
        }
        $starAverage = round($starCount / $reviewCount, 1);
    }

    $reviewText = "ratings";
    if (count($rating) == 1){
        $reviewText = "rating";
    }

    $image = '<img class="bookImg" src= "data:image/png;base64, ' . base64_encode($book->cover) . '" alt="' . $book->title . '" >';
    $tableBody .= <<<HTML
            <div id="container">
                {$image}
                <table id="content">
                    <tr>
                        <td>
                            <table id="data">
                                <tr>
                                    <td><b>Kiadó</b></td>
                                    <td>{$book->getPublisher()->publisher}</td>
                                </tr>
                                <tr>
                                    <td><b>Kategória</b></td>
                                    <td>{$book->getCategory()->category}</td>
                                </tr>
                                <tr>
                                    <td><b>ISBN</b></td>
                                    <td>{$book->ISBN}</td>
                                </tr>
                                <tr>
                                    <td><b>Szerző</b></td>
                                    <td>{$book->getWriter()->writer}</td>
                                </tr>
                                <tr>
                                    <td><b>Ár</b></td>
                                    <td>{$book->price} Ft</td>
                                </tr>
                                <tr>
                                    <td>
                                        <form method='post' action='/edit' class='float-left'>
                                            <input type='hidden' name='id' value='{$book->id}'>
                                            <button type='submit' name='btn-edit' title='Módosít'><i class='fa fa-edit'></i></button>
                                        </form>
                                        <form method='post' action='/'>
                                            <input type='hidden' name='id' value='{$book->id}'>    
                                            <input type='hidden' name='_method' value='DELETE'>
                                            <button type='submit' name='btn-del' title='Töröl'><i class='fa fa-trash trash'></i></button>
                                        </form>
                                    </td>
                                    <td class="rating">
                                        <form method='post' action='/'>
                                            <i class="fa-regular fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                            <br>
                                            {$starAverage} ($reviewCount $reviewText)
                                            <input type='hidden' class='starsValue' name='starsValue' value='{$starAverage}'>
                                            <input type='hidden' name='id' value='{$book->id}'>    
                                            <input type='hidden' name='_method' value='RATE'>
                                            <button type='submit' name='btn-rate' title='Ok'>Ok</button>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div>
                    <div class='title'><b>{$book->title}</b></div>
                    {$book->content}
                </div>
            </div>
            HTML;
}
echo $tableBody . "</div>";
