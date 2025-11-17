<?php

use App\Models\BookModel;
$book = new BookModel();

echo <<<HTML
        <form method='post' action='/' enctype="multipart/form-data">
            <fieldset>
                <table>
                    <tr>
                        <td><label for="writer_id">Író</label></td>
                        <td>
                            <select name="writer_id" id="writer_id">
                                {$book->getWriters()}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="publisher_id">Kiadó</label></td>
                        <td>
                            <select name="publisher_id" id="publisher_id">
                                {$book->getPublishers()}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="category_id">Kategória</label></td>
                        <td>
                            <select name="category_id" id="category_id">
                                {$book->getCategories()}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="title">Cím</label></td>
                        <td><input type="text" name="title" id="title"></td>
                    </tr>
                    <tr>
                        <td><label for="cover">Borítókép</label></td>
                        <td><input type="file" name="cover" id="cover"></td>
                    </tr>
                    <tr>
                        <td><label for="ISBN">ISBN</label></td>
                        <td><input type="text" name="ISBN" id="ISBN"></td>
                    </tr>
                    <tr>
                        <td><label for="price">Ár</label></td>
                        <td><input type="text" name="price" id="price"></td>
                    </tr>
                    <tr>
                        <td><label for="content">Leírás</label></td>
                        <td><textarea type="text" name="content" id="content" class="content"></textarea></td>
                    </tr>
                </table>
                <hr>
                <button type="submit" name="btn-save" class="btn-save">
                    <i class="fa fa-save"></i>&nbsp;Mentés
                </button>
                <a href="/"><i class="fa fa-cancel">                    
                    </i>&nbsp;Mégse
                </a>
            </fieldset>
        </form>
    HTML;