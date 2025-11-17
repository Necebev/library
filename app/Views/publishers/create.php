<?php

echo <<<HTML
        <form method='post' action='/publishers' enctype="multipart/form-data">
            <fieldset>
                <table>
                    <tr>
                        <td><label for="publisher">Kiadó</label></td>
                        <td><input type="text" name="publisher" id="publisher"></td>
                    </tr>
                </table>
                <hr>
                <button type="submit" name="btn-save" class="btn-save">
                    <i class="fa fa-save"></i>&nbsp;Mentés
                </button>
                <a href="/publishers"><i class="fa fa-cancel">                    
                    </i>&nbsp;Mégse
                </a>
            </fieldset>
        </form>
    HTML;