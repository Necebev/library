<?php

echo <<<HTML
        <form method='post' action='/writers' enctype="multipart/form-data">
            <input type='hidden' name='_method' value='PATCH'>
            <input type="hidden" name="id" value="{$writer->id}">
            <fieldset>
                <table>
                    <tr>
                        <td><label for="writer">Író</label></td>
                        <td><input type="text" name="writer" id="writer" value="{$writer->writer}"></td>
                    </tr>
                    <tr>
                        <td><label for="biography">Bio</label></td>
                        <td><textarea type="text" name="biography" id="biography" class="biography">{$writer->biography}</textarea></td>
                    </tr>
                </table>
                <hr>
                <button type="submit" name="btn-update" class="btn-update">
                    <i class="fa fa-save"></i>&nbsp;Mentés
                </button>
                <a href="/writers">
                    <i class="fa fa-cancel"></i>&nbsp;Mégse
                </a>
            </fieldset>
        </form>
    HTML;