document.querySelector(".sidebar-toggle").addEventListener("click", () => {
  document.querySelector(".container").classList.toggle("sidebar-open");
})


function fetchPets(status) {
    fetch(`/HackAnimal/apis/statistics.php?adoptionCenterId=${adoptionCenterId}`)
        .then(res => res.json())
        .then(data => {
        console.log(data);

        const container = document.getElementById("pet-list");
        container.innerHTML = ""; // limpio el contenedor

        const template = document.querySelector(".petcard.template");
        
        
        })
        .catch(err => console.error("Error fetching pets:", err));
}
