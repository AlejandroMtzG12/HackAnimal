document.querySelector(".sidebar-toggle").addEventListener("click", () => {
  document.querySelector(".container").classList.toggle("sidebar-open");
})


document.addEventListener("DOMContentLoaded", () => {
  const menuItems = document.querySelectorAll(".navigation li");
  const adoptionCenterId = 1; 

  function updateSelection(selectedItem) {
    menuItems.forEach(li => {
      li.innerHTML = li.textContent; // reseting all
    });
    selectedItem.innerHTML = `<b><u>${selectedItem.textContent}</u></b>`;
  }

  function fetchPets(status) {
    fetch(`/HackAnimal/apis/myanimals.php?adoptionCenterId=${adoptionCenterId}&status=${status}`)
      .then(res => res.json())
      .then(data => {
        console.log(data);

        const container = document.getElementById("pet-list");
        container.innerHTML = ""; // limpio el contenedor

        const template = document.querySelector(".petcard.template");

        data.forEach(pet => {
          // clonning base card
          const card = template.cloneNode(true);
          card.classList.remove("template");
          card.style.display = "block";

          // data
          card.querySelector(".name").textContent = pet.name;
          card.querySelector(".species").textContent = pet.species;
          card.querySelector(".gender img");
          const genderImg = card.querySelector(".gender img");
          const genderText = card.querySelector(".gender");
          if (pet.gender && pet.gender.toLowerCase() === 'male') {
            genderImg.src = '/hackanimal/img/male.png'; 
          } else if (pet.gender && pet.gender.toLowerCase() === 'female') {
            genderImg.src = '/hackanimal/img/female.png'; 

          } else if (pet.gender && pet.gender.toLowerCase() === 'intersex'){
            genderImg.src = '/hackanimal/img/intersex.png'; 
          } else {
            genderText.setAttribute("data-gender", "Unknown");
          }

         
        //card.querySelector(".petphoto img").alt = pet.species || "pet";

        card.querySelector(".status").innerHTML = `<b>Status:</b> ${pet.status}`;
        card.querySelector(".age").innerHTML = `<b>Age:</b> ${pet.age} years`;
        card.querySelector(".breed").innerHTML = `<b>Breed:</b> ${pet.breed || "N/A"}`;
        card.querySelector(".size").innerHTML = `<b>Size:</b> ${pet.size || "N/A"}`;
        card.querySelector(".color").innerHTML = `<b>Color:</b> ${pet.color || "N/A"}`;
        card.querySelector(".coat").innerHTML = `<b>Coat:</b> ${pet.coat || "N/A"}`;
        card.querySelector(".weight").innerHTML = `<b>Weight:</b> ${pet.weight || "N/A"}`;
        card.querySelector(".sterilization").innerHTML =
          `<b>Sterilized:</b> ${pet.sterilization == "1" ? "Yes" : "No"}`;
        card.querySelector(".vaccines").innerHTML = `<b>Vaccines:</b> ${pet.vaccines || "None"}`;
        card.querySelector(".conditions").innerHTML = `<b>Conditions:</b> ${pet.conditions || "None"}`;
        card.querySelector(".disability").innerHTML = `<b>Disability:</b> ${pet.disability || "None"}`;
        card.querySelector(".description").innerHTML = `<b>Description:</b> ${pet.description || "No description"}`;

          // adding everything
          container.appendChild(card);
        });
      })
      .catch(err => console.error("Error fetching pets:", err));
  }


  // Event listeners for menu items
  menuItems.forEach(li => {
    li.addEventListener("click", () => {
      const status = li.id; // "depending on what you select in the secction is the status"
      updateSelection(li);
      fetchPets(status);
    });
  });

  // Initial load with "Up for adoption"
  const defaultItem = document.getElementById("Upforadoption");
  updateSelection(defaultItem);
  fetchPets("UpForAdoption");
});

