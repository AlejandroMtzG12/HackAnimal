// Toggle sidebar
document.querySelector(".sidebar-toggle").addEventListener("click", () => {
  document.querySelector(".container").classList.toggle("sidebar-open");
});

document.addEventListener("DOMContentLoaded", () => {
  const menuItems = document.querySelectorAll(".navigation li");
  const adoptionCenterId = 1; // puedes reemplazar con la ID real de la sesión si quieres

  // Función para actualizar el menú seleccionado
  function updateSelection(selectedItem) {
    menuItems.forEach(li => {
      li.innerHTML = li.textContent;
    });
    selectedItem.innerHTML = `<b><u>${selectedItem.textContent}</u></b>`;
  }

  // Función para obtener las mascotas según el estado
  function fetchPets(status) {
    fetch(`/HackAnimal/apis/myanimals.php?adoptionCenterId=${adoptionCenterId}&status=${status}`)
      .then(res => res.json())
      .then(data => {
        if (!Array.isArray(data)) {
          data = [data];
        }

        const container = document.getElementById("pet-list");
        container.innerHTML = "";
        const template = document.querySelector(".petcard.template");

        data.forEach(pet => {
          const card = template.cloneNode(true);
          card.classList.remove("template");
          card.style.display = "block";

          // Foto de la mascota
          const petPhoto = card.querySelector(".petphoto img");
          if (pet.image) {
            petPhoto.src = "/hackAnimal/" + pet.image;
            petPhoto.alt = pet.name || "Pet photo";
            petPhoto.style.width = '300px';
          } else {
            petPhoto.src = "/hackAnimal/petImages/no-image.png";
            petPhoto.alt = "No image available";
          }

          // Nombre y especie
          card.querySelector(".name").textContent = pet.name;
          card.querySelector(".species").textContent = pet.species;

          // Género
          const genderImg = card.querySelector(".gender img");
          const genderText = card.querySelector(".gender");
          if (pet.gender && pet.gender.toLowerCase() === 'male') {
            genderImg.src = '/hackanimal/img/male.png'; 
          } else if (pet.gender && pet.gender.toLowerCase() === 'female') {
            genderImg.src = '/hackanimal/img/female.png'; 
          } else if (pet.gender && pet.gender.toLowerCase() === 'intersex') {
            genderImg.src = '/hackanimal/img/intersex.png'; 
          } else {
            genderText.setAttribute("data-gender", "Unknown");
          }

          // Información
          card.querySelector(".status").innerHTML = `<b>Status:</b> ${pet.status}`;
          card.querySelector(".age").innerHTML = `<b>Age:</b> ${pet.age || "N/A"} years`;
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

          container.appendChild(card);
        });
      })
      .catch(err => console.error("Error fetching pets:", err));
  }

  // Eventos de clic en el menú
  menuItems.forEach(li => {
    li.addEventListener("click", () => {
      const status = li.id;
      updateSelection(li);
      fetchPets(status);
    });
  });

  // Carga inicial
  const defaultItem = document.getElementById("UpForAdoption");
  updateSelection(defaultItem);
  fetchPets("UpForAdoption");
});
