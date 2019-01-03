Public Sub flatData01()
    
    For Each sh In Worksheets
        sh.Activate
        If IsEmpty(ActiveSheet.UsedRange) = False Then
            ActiveSheet.UsedRange.AutoFilter
        End If
    Next

End Sub