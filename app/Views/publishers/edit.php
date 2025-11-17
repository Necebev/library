<?php

echo <<<HTML
        <form method='post' action='/publishers' enctype="multipart/form-data">
            <input type='hidden' name='_method' value='PATCH'>
            <input type="hidden" name="id" value="{$publisher->id}">
            <fieldset>
                <table>
                    <tr>
                        <td><label for="publisher">Kiadó</label></td>
                        <td><input type="text" name="publisher" id="publisher" value="{$publisher->publisher}"></td>
                    </tr>
                </table>
                <hr>
                <button type="submit" name="btn-update" class="btn-update">
                    <i class="fa fa-save"></i>&nbsp;Mentés
                </button>
                <a href="/publishers">
                    <i class="fa fa-cancel"></i>&nbsp;Mégse
                </a>
            </fieldset>
        </form>
    HTML;