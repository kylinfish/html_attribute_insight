(function($) {
    let $form = $("form"),
        $input = $("input"),
        $statusMsg = $("p");

    $form.on("submit", function(event){
        event.preventDefault()

        $input.attr("disabled", true);
        $statusMsg.removeClass("invisible");

        let data = $input.val()

        fetch('http://localhost:8000/demo.php',{
            method: 'POST',
            credentials: 'same-origin', // include, *same-origin, omit
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data) // body data type must match "Content-Type" header
        }).then(r => r.json()).then(data => {
            createWordCloud(data["class_mapping"]);
            createTreemap(data["tag_attr_mapping"]);

            $input.attr("disabled", false);
            $statusMsg.hide();
            $statusMsg.addClass("invisible");
        });
    })


    function createWordCloud(data)
    {
        am4core.useTheme(am4themes_animated);
        let chartWordCloud = am4core.create("wordCloudDiv", am4plugins_wordCloud.WordCloud );
        let series = chartWordCloud.series.push(new am4plugins_wordCloud.WordCloudSeries());

        series.randomness = 0.1;
        series.rotationThreshold = 0.5;

        series.data = data

        series.dataFields.word = "tag";
        series.dataFields.value = "count";

        series.labels.template.urlTarget = "_blank";
        series.labels.template.tooltipText = "{word}: {value}";
        series.heatRules.push({
            "target": series.labels.template,
            "property": "fill",
            "min": am4core.color("#0000CC"),
            "max": am4core.color("#CC00CC"),
            "dataField": "value"
        });

        let hoverState = series.labels.template.states.create("hover");
        hoverState.properties.fill = am4core.color("#FF0000");

        let title = chartWordCloud.titles.create();
        title.fontSize = 20;
        title.fontWeight = "800";

        $("#wordCloudDiv").css("height", "650px");
    }

    function createTreemap(data)
    {
        let chartTreeMap = am4core.create("treeMap", am4plugins_forceDirected.ForceDirectedTree);
        let networkSeries = chartTreeMap.series.push(new am4plugins_forceDirected.ForceDirectedSeries());

        networkSeries.data = data;

        networkSeries.dataFields.linkWith = "linkWith";
        networkSeries.dataFields.name = "name";
        networkSeries.dataFields.id = "name";
        networkSeries.dataFields.value = "value";
        networkSeries.dataFields.children = "children";

        networkSeries.links.template.distance = 1;
        networkSeries.nodes.template.label.text = "{name}"
        networkSeries.nodes.template.tooltipText = "{name} x {value}";
        networkSeries.nodes.template.fillOpacity = 1;
        networkSeries.nodes.template.outerCircle.scale = 1;
        networkSeries.nodes.template.label.hideOversized = true;
        networkSeries.nodes.template.label.truncate = true;
        networkSeries.nodes.template.expandAll = false;

        networkSeries.fontSize = 15;
        networkSeries.minRadius = am4core.percent(3);
        networkSeries.maxLevels = 0;
        networkSeries.links.template.strokeOpacity = 5;

        $("#treeMap").css("height", "650px");
    }
})(jQuery)
