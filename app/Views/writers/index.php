<?php

$tableBody = "<div id='writersContainer'>
            <form method='post' action='/writers/create'>
                <button type='submit' name='btn-plus' class='btn-plus' title='Új'>
                    <i class='fa fa-plus plus'></i>&nbsp;Új
                </button>
            </form>";
foreach ($writers as $writer) {
    $tableBody .= <<<HTML
            <div class="writersContainer">
                <div style="width: fit-content; margin: 1vh auto;"><b>{$writer->writer}</b></div>
                <div style="margin: 1vh auto;">{$writer->biography}</div>
                <div style="width: 60px; margin: auto;">
                    <form method='post' action='/writers/edit' class="float-left">
                        <input type='hidden' name='id' value='{$writer->id}'>
                        <button type='submit' name='btn-edit' title='Módosít'><i class='fa fa-edit'></i></button>
                    </form>
                    <form method='post' action='/writers'>
                        <input type='hidden' name='id' value='{$writer->id}'>
                        <input type='hidden' name='_method' value='DELETE'>
                        <button type='submit' name='btn-del' title='Töröl'><i class='fa fa-trash trash'></i></button>
                    </form>
                </div>
            </div>
            HTML;
}
echo $tableBody . "</div>";
