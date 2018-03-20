Function WorksheetExists(WSName As String) As Boolean
    On Error Resume Next
    WorksheetExists = Len(Worksheets(WSName).Name) > 0
End Function

Public Sub createGraph01()

    Dim destSheet As Worksheet
    Dim myRange As Range
    Dim myChart As ChartObject
    
    For Each sh In Worksheets
        If sh.Name <> "Summary" And sh.Name <> "runlog" Then
            sh.Select
            sh.Activate
            sh.Range("A:A").AutoFilter
        End If
    Next
    
    Set destSheet = Worksheets("Summary_Overall")
    
    Worksheets("Variance").Move before := Worksheets(1)
    destSheet.Move before := Worksheets(1)
    destSheet.Activate

    Set myUnion = %s
    
    destSheet.Shapes.AddChart(Left:=120, Top:=40, Width:=900, Height:=400).Select
    ActiveChart.ChartType = xlColumnClustered
    ActiveChart.SetSourceData Source:=myUnion
    ActiveChart.ApplyLayout (3)
    ActiveChart.ClearToMatchStyle
    ActiveChart.ChartStyle = 42
    ActiveChart.ClearToMatchStyle
    
    ActiveChart.Axes(xlValue).Select
    Selection.TickLabels.NumberFormat = "0X"
    
    ActiveChart.ChartTitle.Text = "ShaderBench Performance relative to SCPC - %s"

    Range("%s").Select
    Selection.FormatConditions.AddColorScale ColorScaleType:=3
    Selection.FormatConditions(Selection.FormatConditions.Count).SetFirstPriority
    Selection.FormatConditions(1).ColorScaleCriteria(1).Type = _
        xlConditionValueLowestValue
    With Selection.FormatConditions(1).ColorScaleCriteria(1).FormatColor
        .Color = 7039480
        .TintAndShade = 0
    End With
    Selection.FormatConditions(1).ColorScaleCriteria(2).Type = _
        xlConditionValuePercentile
    Selection.FormatConditions(1).ColorScaleCriteria(2).Value = 50
    With Selection.FormatConditions(1).ColorScaleCriteria(2).FormatColor
        .Color = 16776444
        .TintAndShade = 0
    End With
    Selection.FormatConditions(1).ColorScaleCriteria(3).Type = _
        xlConditionValueHighestValue
    With Selection.FormatConditions(1).ColorScaleCriteria(3).FormatColor
        .Color = 8109667
        .TintAndShade = 0
    End With
    
    %s
    
    Range("A1:A1").Select
    
End Sub