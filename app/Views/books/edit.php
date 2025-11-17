<?php

$html = <<<HTML
    <form method='post' action='/' enctype="multipart/form-data">
        <input type='hidden' name='_method' value='PATCH'>
        <input type="hidden" name="id" value="{$book->id}">
        <fieldset>
            <table>
                <tr>
                    <td><label for="writer_id">Író</label></td>
                    <td>
                        <select name="writer_id" id="writer_id">
                            {$book->getWriters($book->writer_id)}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="publisher_id">Kiadó</label></td>
                    <td>
                        <select name="publisher_id" id="publisher_id">
                            {$book->getPublishers($book->publisher_id)}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="category_id">Kategória</label></td>
                    <td>
                        <select name="category_id" id="category_id">
                            {$book->getCategories($book->category_id)}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="title">Cím</label></td>
                    <td><input type="text" name="title" id="title" value="{$book->title}"></td>
                </tr>
                <tr>
                    <td><label for="cover">Borítókép</label></td>
                    <td><input type="file" name="cover" id="cover"></td>
                </tr>
                <tr>
                    <td><label for="ISBN">ISBN</label></td>
                    <td><input type="text" name="ISBN" id="ISBN" value="{$book->ISBN}"></td>
                </tr>
                <tr>
                    <td><label for="price">Ár</label></td>
                    <td><input type="text" name="price" id="price" value="{$book->price}"></td>
                </tr>
                <tr>
                    <td><label for="content">Leírás</label></td>
                    <td><textarea type="text" name="content" id="content" class="content">{$book->content}</textarea></td>
                </tr>
            </table>
            <hr>
            <button type="submit" name="btn-update" class="btn-update">
                <i class="fa fa-save"></i>&nbsp;Mentés
            </button>
            <a href="/">
                <i class="fa fa-cancel"></i>&nbsp;Mégse
            </a>
        </fieldset>
    </form>
HTML;

echo $html;