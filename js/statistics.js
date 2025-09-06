document.querySelector(".sidebar-toggle").addEventListener("click", () => {
  document.querySelector(".container").classList.toggle("sidebar-open");
})

function loadCharts(adoptionCenterId) {
    fetch(`/HackAnimal/apis/statistics.php?adoptionCenterId=${adoptionCenterId}`)
        .then(res => res.json())
        .then(data => {
            console.log("Datos recibidos:", data);

            // --------- QUERY1: Pets by species ---------
            const speciesnames = data.query1.map(item => item.species);
            const speciescount = data.query1.map(item => item.total);

            createChart("chartSpecies", 'pie', speciesnames, speciescount,
                ['#2d8bba','#41b8d5','#6ce5e8'], "No data");

            // --------- QUERY2 + QUERY3 + QUERY4: Adoption Status ---------
            const adoptionData = [...data.query2, ...data.query3, ...data.query4];

            const dogStatus = adoptionData.filter(item => item.species === "Dog");
            const catStatus = adoptionData.filter(item => item.species === "Cat");
            const smallStatus = adoptionData.filter(item => item.species === "SmallMammal");

            createChart("chartStatusDog", 'pie',
                dogStatus.map(i => i.status),
                dogStatus.map(i => i.total),
                ['#2d8bba','#41b8d5','#6ce5e8','#6c757d'],
                "No data");

            createChart("chartStatusCat", 'pie',
                catStatus.map(i => i.status),
                catStatus.map(i => i.total),
                ['#2d8bba','#41b8d5','#6ce5e8','#6c757d'],
                "No data");

            createChart("chartStatusSmallMammal", 'pie',
                smallStatus.map(i => i.status),
                smallStatus.map(i => i.total),
                ['#2d8bba','#41b8d5','#6ce5e8','#6c757d'],
                "No data");

            // --------- QUERY5: Sterilization ---------
            const sterilizationData = data.query5;

            const dogSter = sterilizationData.filter(item => item.species === "Dog");
            const catSter = sterilizationData.filter(item => item.species === "Cat");
            const smallSter = sterilizationData.filter(item => item.species === "SmallMammal");

            createChart("chartSterilizationDog", 'pie',
                dogSter.map(i => i.sterilization === "1" ? "Yes" : "No"),
                dogSter.map(i => i.total),
                ['#2d8bba','#6ce5e8'],
                "No data");

            createChart("chartSterilizationCat", 'pie',
                catSter.map(i => i.sterilization === "1" ? "Yes" : "No"),
                catSter.map(i => i.total),
                ['#2d8bba','#6ce5e8'],
                "No data");

            createChart("chartSterilizationSmallMammal", 'pie',
                smallSter.map(i => i.sterilization === "1" ? "Yes" : "No"),
                smallSter.map(i => i.total),
                ['#2d8bba','#6ce5e8'],
                "No data");
        });
}



function createChart(canvasId, type, labels, values, colors, emptyLabel) {
    const ctx = document.getElementById(canvasId);

  
    if (!labels.length || !values.length) {
        labels = [emptyLabel];
        values = [1];
        colors = ["#d3d3d3"]; 
    }

    new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
}


loadCharts(1)
