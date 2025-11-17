window.onload = () => {
    if (window.location.href == 'http://localhost:8000/'){

        let listOptions = document.getElementById('listOptions');
        let table = document.getElementById('table');
        let attribute = document.getElementById('attribute');
        let searchByAttribute = document.getElementById('searchByAttribute');
        let searchByTable = document.getElementById('searchByTable');
        let attributes = {'címek': {'table': 'books', 'attribute': 'title'},
                              'írók': {'table': 'writers', 'attribute': 'writer'},
                              'kiadók': {'table': 'publishers', 'attribute': 'publisher'},
                              'kategóriák': {'table': 'categories', 'attribute': 'category'}}

        listOptions.onchange = () => {
            let selected = listOptions.options[listOptions.selectedIndex].text;
            table.value = attributes[selected]['table'];
            searchByTable.value = attributes[selected]['table'];
            attribute.value = attributes[selected]['attribute'];
            searchByAttribute.value = attributes[selected]['attribute'];
        };

        
    }
};