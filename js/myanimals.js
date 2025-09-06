document.querySelector(".sidebar-toggle").addEventListener("click", () => {
  document.querySelector(".container").classList.toggle("sidebar-open");
});

document.addEventListener("DOMContentLoaded", () => {
  const menuItems = document.querySelectorAll(".navigation li");

  function updateSelection(selectedItem) {
    menuItems.forEach(li => {
      li.innerHTML = li.textContent;
    });
    selectedItem.innerHTML = `<b><u>${selectedItem.textContent}</u></b>`;
  }

  function fetchPets(status) {
    fetch(`/HackAnimal/apis/myanimals.php?status=${status}`)
      .then(res => res.json())
      .then(data => {
        // 📸 Foto del shelter
        const photobox = document.getElementById("photobox");
        if (data.photo) {
          photobox.innerHTML = `<img src="${data.photo}" 
                                  alt="Shelter Photo" 
                                  style="width:100%; height:100%; border-radius:50%;">`;
        } else {
          photobox.innerHTML = `<img src="petImages/no-image.png" 
                                  alt="No shelter photo" 
                                  style="width:100%; height:100%; border-radius:50%;">`;
        }

        // 🐾 Mascotas
        let pets = data.pets || [];
        if (!Array.isArray(pets)) {
          pets = [pets];
        }

        const container = document.getElementById("pet-list");
        container.innerHTML = "";
        const template = document.querySelector(".petcard.template");

        pets.forEach(pet => {
          const card = template.cloneNode(true);
          card.classList.remove("template");
          card.style.display = "block";

          // Foto mascota
          const petPhoto = card.querySelector(".petphoto img");
          if (pet.photo || pet.image) {
            const photoPath = pet.photo || pet.image;
            petPhoto.src = photoPath;
            petPhoto.alt = pet.name || "Pet photo";
            petPhoto.style.width = "300px";
          } else {
            petPhoto.src = "petImages/no-image.png";
            petPhoto.alt = "No image available";
          }

          // Info mascota
          card.querySelector(".name").textContent = pet.name;
          card.querySelector(".species").textContent = pet.species;

          const genderImg = card.querySelector(".gender img");
          const genderText = card.querySelector(".gender");
          if (pet.gender && pet.gender.toLowerCase() === "male") {
            genderImg.src = "img/male.png";
          } else if (pet.gender && pet.gender.toLowerCase() === "female") {
            genderImg.src = "img/female.png";
          } else if (pet.gender && pet.gender.toLowerCase() === "intersex") {
            genderImg.src = "img/intersex.png";
          } else {
            genderText.setAttribute("data-gender", "Unknown");
          }

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

          // Botón editar
          const editBtn = card.querySelector(".editbtn");
          editBtn.addEventListener("click", () => {
            window.location.href = `/HackAnimal/editpet.php?id=${pet.pet_id}`;
          });

          container.appendChild(card);
        });
      })
      .catch(err => console.error("Error fetching pets:", err));
  }

  // Eventos menú
  menuItems.forEach(li => {
    li.addEventListener("click", () => {
      const status = li.id;
      updateSelection(li);
      fetchPets(status);
    });
  });

  // Default: mascotas en adopción
  const defaultItem = document.getElementById("UpForAdoption");
  updateSelection(defaultItem);
  fetchPets("UpForAdoption");
});
