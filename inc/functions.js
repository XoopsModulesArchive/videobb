var marked_row = new Array;

function testMouse(theRow, theRowNum, theAction, theDefaultColor, thePointerColor, theMarkColor)
{
    var theCells = null;

    if ((thePointerColor == '' && theMarkColor == '')
        || typeof(theRow.style) == 'undefined') {
        return false;
    }

    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }
    else {
        return false;
    }

    var rowCellsCnt  = theCells.length;
    var domDetect    = null;
    var currentColor = null;
    var newColor     = null;

    if (typeof(window.opera) == 'undefined'
        && typeof(theCells[0].getAttribute) != 'undefined') {
        currentColor = theCells[0].getAttribute('bgcolor');
        domDetect    = true;
    }
    else {
        currentColor = theCells[0].style.backgroundColor;
        domDetect    = false;
    }

    if (currentColor == null
        || currentColor.toLowerCase() == theDefaultColor.toLowerCase()) {
        if (theAction == 'over' && thePointerColor != '') {
            newColor              = thePointerColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
        }
    }
    else if (currentColor.toLowerCase() == thePointerColor.toLowerCase()
             && (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])) {
        if (theAction == 'out') {
            newColor              = theDefaultColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
        }
    }
    else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
        if (theAction == 'click') {
            newColor              = (thePointerColor != '')
                                  ? thePointerColor
                                  : theDefaultColor;
            marked_row[theRowNum] = (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])
                                  ? true
                                  : null;
        }
    }

    if (newColor) {
        var c = null;
        if (domDetect) {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].setAttribute('bgcolor', newColor, 0);
            } 
        }
        else {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].style.backgroundColor = newColor;
            }
        }
    }

    return true;
}

function saveNodes(f,n)
{
    f.appendChild(n);

    if ( n.nodeType != 3 /* Node.TEXT_NODE*/ )
    {
        var kids = n.childNodes;
        var numkids = kids.length;

        for ( var i = 0; i < numkids; i++ )
            saveNodes(f, kids[i] );
    }
}

function toggleMediaGenreBox()
{
    if ( !toggleMediaGenreBox.state || toggleMediaGenreBox.state != 1 )
    {
        toggleMediaGenreBox.state = 1;

        toggleMediaGenreBox.box = document.getElementById('media_genre');

        if ( !toggleMediaGenreBox.box )
        {
            toggleMediaGenreBox.state = 3;
        }

        if ( !toggleMediaGenreBox.boxFragment1 )
        {
            toggleMediaGenreBox.boxFragment1 = document.createDocumentFragment();
            saveNodes( toggleMediaGenreBox.boxEditFragment1, toggleMediaGenreBox.box );
        }

        if ( !toggleMediaGenreBox.boxFragment2 )
        {
            toggleMediaGenreBox.boxFragment2 = document.createElement("div");
            toggleMediaGenreBox.boxFragment2.innerHTML = "<h1>a</h1>";
        }

        toggleMediaGenreBox.box.parent.replaceChild(toggleMediaGenreBox.boxFragment2,toggleMediaGenreBox.box);
    }
    else if ( toggleMediaGenreBox.state == 1 )
    {
        toggleMediaGenreBox.state = 2;

        toggleMediaGenreBox.box = document.getElementById('media_genre');

        toggleMediaGenreBox.box.parent.replaceChild(toggleMediaGenreBox.boxFragment1,toggleMediaGenreBox.box);
    }
}