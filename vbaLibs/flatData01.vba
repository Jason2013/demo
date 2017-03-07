Public Sub flatData01()
    
    For Each sh In Worksheets
        sh.Activate
        ActiveSheet.UsedRange.AutoFilter
    Next

End Sub