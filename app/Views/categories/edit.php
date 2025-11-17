<?php

echo <<<HTML
        <form method='post' action='/categories' enctype="multipart/form-data">
            <input type='hidden' name='_method' value='PATCH'>
            <input type="hidden" name="id" value="{$category->id}">
            <fieldset>
                <table>
                    <tr>
                        <td><label for="category">Kategória</label></td>
                        <td><input type="text" name="category" id="category" value="{$category->category}"></td>
                    </tr>
                </table>
                <hr>
                <button type="submit" name="btn-update" class="btn-update">
                    <i class="fa fa-save"></i>&nbsp;Mentés
                </button>
                <a href="/categories">
                    <i class="fa fa-cancel"></i>&nbsp;Mégse
                </a>
            </fieldset>
        </form>
    HTML;