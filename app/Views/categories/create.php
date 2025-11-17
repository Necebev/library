<?php

echo <<<HTML
        <form method='post' action='/categories' enctype="multipart/form-data">
            <fieldset>
                <table>
                    <tr>
                        <td><label for="category">Kategória</label></td>
                        <td><input type="text" name="category" id="category"></td>
                    </tr>
                </table>
                <hr>
                <button type="submit" name="btn-save" class="btn-save">
                    <i class="fa fa-save"></i>&nbsp;Mentés
                </button>
                <a href="/categories"><i class="fa fa-cancel">                    
                    </i>&nbsp;Mégse
                </a>
            </fieldset>
        </form>
    HTML;