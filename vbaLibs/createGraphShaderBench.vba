Function WorksheetExists(WSName As String) As Boolean
    On Error Resume Next
    WorksheetExists = Len(Worksheets(WSName).Name) > 0
End Function

Function setCharBarColor(testNum As Integer)
    Dim i As Integer
    Dim f1 As Single
    Dim t1 As String
    
    ActiveSheet.ChartObjects("chart02").Activate
    For i = 1 To testNum
        ActiveChart.SeriesCollection(1).Points(i).Select
        t1 = Selection.DataLabel.Text
        t1 = Left(t1, Len(t1) - 1)
        f1 = Val(t1)
        If f1 < 0 Then
            With Selection.Format.Fill
                .Visible = msoTrue
                .ForeColor.RGB = RGB(146, 208, 80)
                .Solid
            End With
        Else
            With Selection.Format.Fill
                .Visible = msoTrue
                .ForeColor.RGB = RGB(208, 0, 80)
                .Solid
            End With
        End If
    Next
    For i = (testNum + 1) To (testNum + testNum)
        ActiveChart.SeriesCollection(1).Points(i).Select
        t1 = Selection.DataLabel.Text
        t1 = Left(t1, Len(t1) - 1)
        f1 = Val(t1)
        If f1 < 0 Then
            With Selection.Format.Fill
                .Visible = msoTrue
                .ForeColor.RGB = RGB(146, 208, 80)
                .Solid
            End With
        Else
            With Selection.Format.Fill
                .Visible = msoTrue
                .ForeColor.RGB = RGB(208, 0, 80)
                .Solid
            End With
        End If
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
    
    'For i = 1 To testNum
    '    ActiveChart.SeriesCollection(1).Points(i).Select
    '    f1 = Selection.DataLabel.Text + 0.0
    '    
    '    With Selection.Format.Fill
    '        .Visible = msoTrue
    '        .ForeColor.RGB = RGB(146, 208, 80)
    '        .Solid
    '    End With
    'Next
    'For i = (testNum + 1) To (testNum + testNum)
    '    ActiveChart.SeriesCollection(1).Points(i).Select
    '    With Selection.Format.Fill
    '        .Visible = msoTrue
    '        .ForeColor.RGB = RGB(208, 0, 80)
    '        .Solid
    '    End With
    'Next
    
    'ActiveSheet.ChartObjects("chart01").Activate
    'For i = 1 To testNum
    '    ActiveChart.SeriesCollection(2).Points(i).Select
    '    With Selection.Format.Fill
    '        .Visible = msoTrue
    '        .ForeColor.RGB = RGB(146, 208, 80)
    '        .Solid
    '    End With
    'Next
    'For i = (testNum + 1) To (testNum + testNum)
    '    ActiveChart.SeriesCollection(2).Points(i).Select
    '    With Selection.Format.Fill
    '        .Visible = msoTrue
    '        .ForeColor.RGB = RGB(208, 0, 80)
    '        .Solid
    '    End With
    'Next
End Function

Function setCharBarPercentTag(barNum1 As Integer, barNum2 As Integer)
    Dim i As Integer
    ActiveSheet.ChartObjects("chart02").Activate
    For i = 1 To barNum2
        'If (i Mod 2) = 1 Then
            ActiveChart.SeriesCollection(i).Select
            ActiveChart.SeriesCollection(i).ApplyDataLabels
            ActiveChart.SeriesCollection(i).DataLabels.Select
            Selection.NumberFormat = "0.00%%"
            Selection.Format.TextFrame2.TextRange.Font.Size = 10
        'End If
    Next
    
    ActiveSheet.ChartObjects("chart01").Activate
    For i = 1 To barNum1
        'If (i Mod 2) = 1 Then
            ActiveChart.SeriesCollection(i).Select
            ActiveChart.SeriesCollection(i).ApplyDataLabels
            ActiveChart.SeriesCollection(i).DataLabels.Select
            Selection.NumberFormat = "0.00%%"
            Selection.Format.TextFrame2.TextRange.Font.Size = 10
        'End If
    Next
    
    'ActiveSheet.ChartObjects("chart02").Activate
    'ActiveChart.SeriesCollection(1).Select
    'ActiveChart.SeriesCollection(1).ApplyDataLabels
    'ActiveChart.SeriesCollection(1).DataLabels.Select
    'Selection.NumberFormat = "0.00%%"
    '
    'ActiveSheet.ChartObjects("chart01").Activate
    'If barNum1 > 1 Then
    '    ActiveChart.SeriesCollection(2).Select
    '    ActiveChart.SeriesCollection(2).ApplyDataLabels
    '    ActiveChart.SeriesCollection(2).DataLabels.Select
    '    Selection.NumberFormat = "0.00%%"
    'End If

End Function

Function removeChartLegend()
    ActiveSheet.ChartObjects("chart02").Activate
    ActiveChart.Legend.Select
    Selection.Delete
End Function

Function removeMBOutUserSecChart()
    ActiveSheet.ChartObjects("chart01").Activate
    ActiveChart.ChartArea.Select
    ActiveChart.Parent.Delete
End Function

Function setSecondTitleColor(MainTitle As String, SecondTitle As String)
    Dim t1 As String
    
    ActiveSheet.ChartObjects("chart03").Activate
    ActiveChart.ChartTitle.Select
    Selection.Format.TextFrame2.TextRange.Font.Size = 14
    ActiveChart.ChartTitle.Text = _
        MainTitle & Chr(13) & SecondTitle
    Selection.Format.TextFrame2.TextRange.Characters.Text = _
        MainTitle & Chr(13) & SecondTitle
    With Selection.Format.TextFrame2.TextRange.Characters(1, Len(MainTitle) + 1).ParagraphFormat
        .TextDirection = msoTextDirectionLeftToRight
        .Alignment = msoAlignCenter
    End With
    
    With Selection.Format.TextFrame2.TextRange.Characters(Len(MainTitle)+ 2, Len(SecondTitle)).Font
        .BaselineOffset = 0
        .Bold = msoTrue
        .NameComplexScript = "+mn-cs"
        .NameFarEast = "+mn-ea"
        .Fill.Visible = msoTrue
        .Fill.ForeColor.RGB = RGB(255, 192, 0)
        .Fill.Transparency = 0
        .Fill.Solid
        .Size = 14
        .Italic = msoFalse
        .Kerning = 12
        .Name = "+mn-lt"
        .UnderlineStyle = msoNoUnderline
        .Strike = msoNoStrike
    End With
    ActiveChart.Legend.Select
    Selection.Format.TextFrame2.TextRange.Font.Size = 12
    Selection.Position = xlTop

End Function

Function SetSheetGraph(tmpSheetName As String, tmpUnion As String, tmpTitle As String, tmpNoBlank As String, MainTitle As String, SecondTitle As String)
    
    Dim destSheet As Worksheet
    Dim myRange As Range
    Dim myChart As ChartObject
    Dim myMainTitle As String
    Dim myNumFormat As String
    
    If WorksheetExists(tmpSheetName) = True Then
        Set destSheet = Worksheets(tmpSheetName)
        myNumFormat = "0%%"
    End If
    
    destSheet.Move before := Worksheets(1)
    destSheet.Activate

    Set myUnion = destSheet.Range(tmpUnion)
    Set myChart = destSheet.ChartObjects.Add(800, 40, 1500, 400)

    myChart.Chart.ChartType = xlColumnClustered
    myChart.Chart.SetSourceData Source:=myUnion, PlotBy:=xlColumns
    myChart.Chart.ApplyDataLabels ShowValue:=False
    myChart.Chart.HasTitle = True
    myChart.Chart.ChartTitle.Text = tmpTitle
    myChart.Name = "chart03"

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
    
    Range(tmpNoBlank).Select
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
    
    ActiveSheet.ChartObjects("chart03").Activate
    ActiveChart.ChartTitle.Select
    Selection.Format.TextFrame2.TextRange.Font.Size = 14
    ActiveChart.Legend.Select
    Selection.Format.TextFrame2.TextRange.Font.Size = 12
    Selection.Position = xlTop
    For Each tmpTag In ActiveChart.Axes
        tmpTag.TickLabels.Font.Size = 12
    Next
    
    Call setSecondTitleColor(MainTitle, SecondTitle)
    
    Range("A1:A1").Select
    
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
    
    
    If WorksheetExists("Variation") = True Then
        Worksheets("Variation").Move before := Worksheets(1)
    End If
    
    
    %s
    
End Sub