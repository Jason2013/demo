function swtImplode(_valList, _sep)
{
    var t1 = "";
    for (var i = 0; i < _valList.length; i++)
    {
        t1 += _valList[i];
        if (i < (_valList.length - 1))
        {
            t1 += _sep;
        }
    }
    return t1;
}

function swtUniqueArray(_valList)
{
    var tmpList = [];
    var tmpMap = {};
    for (var i = 0; i < _valList.length; i++)
    {
        if (tmpMap[_valList[i]] != true)
        {
            tmpList.push(_valList[i]);
            tmpMap[_valList[i]] = true;
        }
    }
    return tmpList;
}

function swtGotoPage(_link)
{
    window.location.href = _link;
}