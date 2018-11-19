Function WorksheetExists(WSName As String) As Boolean
    On Error Resume Next
    WorksheetExists = Len(Worksheets(WSName).Name) > 0
End Function

Function setCharBarColor(testNum As Integer)
    Dim i As Integer
    ActiveSheet.ChartObjects("chart02").Activate
    For i = 1 To testNum
        ActiveChart.SeriesCollection(1).Points(i).Select
        With Selection.Format.Fill
            .Visible = msoTrue
            .ForeColor.RGB = RGB(146, 208, 80)
            .Solid
        End With
    Next
    For i = (testNum + 1) To (testNum + testNum)
        ActiveChart.SeriesCollection(1).Points(i).Select
        With Selection.Format.Fill
            .Visible = msoTrue
            .ForeColor.RGB = RGB(208, 0, 80)
            .Solid
        End With
    Next
    
    
    ActiveSheet.ChartObjects("chart01").Activate
    For i = 1 To testNum
        ActiveChart.SeriesCollection(2).Points(i).Select
        With Selection.Format.Fill
            .Visible = msoTrue
            .ForeColor.RGB = RGB(146, 208, 80)
            .Solid
        End With
    Next
    For i = (testNum + 1) To (testNum + testNum)
        ActiveChart.SeriesCollection(2).Points(i).Select
        With Selection.Format.Fill
            .Visible = msoTrue
            .ForeColor.RGB = RGB(208, 0, 80)
            .Solid
        End With
    Next
End Function

Public Sub createGraph01()

    Dim destSheet As Worksheet
    Dim myRange As Range
    Dim myChart As ChartObject
    Dim myMainTitle As String
    Dim myNumFormat As String
    
    For Each sh In Worksheets
        If sh.Name <> "Summary" And sh.Name <> "runlog" Then
            sh.Select
            sh.Activate
            sh.Range("A:A").AutoFilter
        End If
    Next
    
    'Set destSheet = Worksheets("Cross-API_Comparison")
    
    If WorksheetExists("Cross-API_Comparison") = True Then
        Set destSheet = Worksheets("Cross-API_Comparison")
        'myMainTitle = "Microbench Performance relative to DXX - "
        myNumFormat = "0%%"
    ElseIf WorksheetExists("Summary_Overall") = True Then
        Set destSheet = Worksheets("Summary_Overall")
        'myMainTitle = "ShaderBench Performance relative to SCPC - "
        myNumFormat = "0%%"
    End If
    
    If WorksheetExists("Variance") = True Then
        Worksheets("Variance").Move before := Worksheets(1)
    End If
    
    destSheet.Move before := Worksheets(1)
    destSheet.Activate

    Set myUnion = %s
    Set myChart = destSheet.ChartObjects.Add(800, 500, 1500, 400)

    myChart.Chart.ChartType = xlColumnClustered
    myChart.Chart.SetSourceData Source:=myUnion, PlotBy:=xlColumns
    myChart.Chart.ApplyDataLabels ShowValue:=False
    myChart.Chart.HasTitle = True
    myChart.Chart.ChartTitle.Text = "%s"
    myChart.Name = "chart01"

    myChart.Activate

    ActiveChart.PlotArea.Select
    Selection.Format.Fill.Visible = msoFalse
    ActiveChart.ChartTitle.Select
    With Selection.Format.TextFrame2.TextRange.Font
        .BaselineOffset = 0
        .Fill.Visible = msoTrue
        .Fill.ForeColor.ObjectThemeColor = msoThemeColorBackground1
        .Fill.ForeColor.TintAndShade = 0
        .Fill.ForeColor.Brightness = 0
        .Fill.Transparency = 0
        .Fill.Solid
    End With
    ActiveChart.Legend.Select
    With Selection.Format.TextFrame2.TextRange.Font
        .BaselineOffset = 0
        .Fill.Visible = msoTrue
        .Fill.ForeColor.ObjectThemeColor = msoThemeColorBackground2
        .Fill.ForeColor.TintAndShade = 0
        .Fill.ForeColor.Brightness = 0
        .Fill.Transparency = 0
        .Fill.Solid
    End With

    ActiveChart.Axes(xlCategory).TickLabels.Font.Color = RGB(200, 200, 200)
    ActiveChart.Axes(xlCategory).TickLabelPosition = xlLow

    ActiveChart.Axes(xlValue).TickLabels.Font.Color = RGB(200, 200, 200)

    ActiveChart.Axes(xlValue).Select
    Selection.TickLabels.NumberFormat = myNumFormat

    
    ActiveChart.Legend.Select
    Selection.Position = xlBottom
    
    ActiveChart.ChartArea.Interior.Color = RGB(60, 60, 60)
    
    ActiveChart.ClearToMatchStyle
    ActiveChart.ChartStyle = 42
    ActiveChart.ClearToMatchStyle
    
    ActiveChart.Axes(xlValue).Select
    Selection.TickLabels.Font.Size = 11
    Selection.TickLabels.Font.Bold = msoTrue
    ActiveChart.Axes(xlCategory).Select
    Selection.TickLabels.Font.Size = 11
    Selection.TickLabels.Font.Bold = msoTrue
    Selection.MajorTickMark = xlNone
    
    'With ActiveSheet.Shapes("chart01").Fill
    '    .Visible = msoTrue
    '    .ForeColor.RGB = RGB(0, 0, 0) 
    '    .ForeColor.TintAndShade = 0.0
    '    .ForeColor.Brightness = 0
    '    .BackColor.RGB = RGB(0, 0, 0) 
    '    .BackColor.TintAndShade = 0.05
    '    .BackColor.Brightness = 0.05
    '    .TwoColorGradient msoGradientVertical, 2
    'End With
    'ActiveChart.PlotArea.Format.Fill.Visible = msoFalse
    
    
    Set myUnion = %s
    Set myChart = destSheet.ChartObjects.Add(800, 40, 1500, 400)

    myChart.Chart.ChartType = xlColumnClustered
    myChart.Chart.SetSourceData Source:=myUnion, PlotBy:=xlColumns
    myChart.Chart.ApplyDataLabels ShowValue:=False
    myChart.Chart.HasTitle = True
    myChart.Chart.ChartTitle.Text = "%s"
    myChart.Name = "chart02"

    myChart.Activate

    ActiveChart.PlotArea.Select
    Selection.Format.Fill.Visible = msoFalse
    ActiveChart.ChartTitle.Select
    With Selection.Format.TextFrame2.TextRange.Font
        .BaselineOffset = 0
        .Fill.Visible = msoTrue
        .Fill.ForeColor.ObjectThemeColor = msoThemeColorBackground1
        .Fill.ForeColor.TintAndShade = 0
        .Fill.ForeColor.Brightness = 0
        .Fill.Transparency = 0
        .Fill.Solid
    End With
    ActiveChart.Legend.Select
    With Selection.Format.TextFrame2.TextRange.Font
        .BaselineOffset = 0
        .Fill.Visible = msoTrue
        .Fill.ForeColor.ObjectThemeColor = msoThemeColorBackground2
        .Fill.ForeColor.TintAndShade = 0
        .Fill.ForeColor.Brightness = 0
        .Fill.Transparency = 0
        .Fill.Solid
    End With

    ActiveChart.Axes(xlCategory).TickLabels.Font.Color = RGB(200, 200, 200)
    ActiveChart.Axes(xlCategory).TickLabelPosition = xlLow

    ActiveChart.Axes(xlValue).TickLabels.Font.Color = RGB(200, 200, 200)

    ActiveChart.Axes(xlValue).Select
    Selection.TickLabels.NumberFormat = myNumFormat

    
    ActiveChart.Legend.Select
    Selection.Position = xlBottom
    
    ActiveChart.ChartArea.Interior.Color = RGB(60, 60, 60)
    
    ActiveChart.ClearToMatchStyle
    ActiveChart.ChartStyle = 42
    ActiveChart.ClearToMatchStyle
    
    ActiveChart.Axes(xlValue).Select
    Selection.TickLabels.Font.Size = 11
    Selection.TickLabels.Font.Bold = msoTrue
    ActiveChart.Axes(xlCategory).Select
    Selection.TickLabels.Font.Size = 11
    Selection.TickLabels.Font.Bold = msoTrue
    Selection.MajorTickMark = xlNone
    
    ActiveChart.SeriesCollection(1).Select
    ActiveChart.SeriesCollection(1).ApplyDataLabels
    ActiveChart.SeriesCollection(1).DataLabels.Select
    Selection.NumberFormat = "0.00%%"
    
    'With ActiveSheet.Shapes("chart02").Fill
    '    .Visible = msoTrue
    '    .ForeColor.RGB = RGB(0, 0, 0) 
    '    .ForeColor.TintAndShade = 0.0
    '    .ForeColor.Brightness = 0
    '    .BackColor.RGB = RGB(0, 0, 0) 
    '    .BackColor.TintAndShade = 0.2
    '    .BackColor.Brightness = 0
    '    .TwoColorGradient msoGradientVertical, 1
    'End With
    'ActiveChart.PlotArea.Format.Fill.Visible = msoFalse
    

    Range("%s").Select
    Selection.FormatConditions.AddColorScale ColorScaleType:=3
    Selection.FormatConditions(Selection.FormatConditions.Count).SetFirstPriority
    Selection.FormatConditions(1).ColorScaleCriteria(1).Type = _
        xlConditionValueLowestValue
    With Selection.FormatConditions(1).ColorScaleCriteria(1).FormatColor
        .Color = 8109667
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
        .Color = 7039480
        .TintAndShade = 0
    End With
    
    
    %s
    
    Range("A1:A1").Select
    
End Sub