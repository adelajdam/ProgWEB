const products = [
    {
        id: 1,
        name: "Anua Heartleaf Cleanser",
        price: "1200L",
        stock: "Ka stok",
        img: "https://roots-pharmacy.com/cdn/shop/files/8f022834-f1a4-44eb-ab36-cff42ea140c7.jpg?v=1762201031&width=1000",
        desc: "Krem qetësues dhe hidratues që ndihmon lëkurën të jetë e shëndetshme dhe e butë.",
        ingredients: ["Ingredient 1", "Ingredient 2", "Ingredient 3"]
    },
    {
        id: 2,
        name: "Anua Heartleaf 70 Soothing Collagen Mask",
        price: "300L",
        stock: "Ka stok",
        img: "https://th.bing.com/th?id=OIF.Lu4bTrFi8mPp%2BIWZG%2FDIeg&cb=ucfimg2&ucfimg=1&rs=1&pid=ImgDetMain&o=7&rm=3",
        desc: "Mask qetësues dhe hidratues me kolagjen për lëkurë të ndritshme.",
        ingredients: ["Ingredient A", "Ingredient B"]
    },
    {
        id: 3,
        name: "Anua Heartleaf Intense Calming Cream",
        price: "1300L",
        stock: "Ka stok",
        img: "https://www.scentsational.com/images/anua-heartleaf-intense-calming-cream-50ml-p49402-95490_medium.jpg",
        desc: "Krem intensiv qetësues që ndihmon lëkurën të rigjenerohet.",
        ingredients: ["Ingredient X", "Ingredient Y", "Ingredient Z"]
    },
    {
        id: 4,
        name: "BEAUTY OF JOESON Relief Sun Rice + Probiotics SPF 50",
        price: "1700L",
        stock: "Ka stok",
        img: "https://m.media-amazon.com/images/I/41ZNRwLuTRL.jpg",
        desc: "Sun cream me SPF 50 që mbron dhe kujdeset për lëkurën.",
        ingredients: ["Ingredient 1", "Ingredient 2"]
    },
    {
        id: 5,
        name: "COSRX Snail Mucin 96%",
        price: "2000L",
        stock: "Ka stok",
        img: "https://m.media-amazon.com/images/I/51IF5kpotSL.jpg",
        desc: "Serum me mukus të kallamit të gjarprit për rigjenerim të lëkurës.",
        ingredients: ["Ingredient A", "Ingredient B"]
    },
    {
        id: 6,
        name: "innisfree Cherry Blossom Glow Jam Cleanser",
        price: "1900L",
        stock: "Ka stok",
        img: "https://m.media-amazon.com/images/I/61KeAJa4x6S._SL1500_.jpg",
        desc: "Cleanser me ekstrakt qershie për lëkurë të ndritshme dhe të butë.",
        ingredients: ["Ingredient X", "Ingredient Y"]
    }
];

// Ngarkimi i produkteve në faqen kryesore
const container = document.getElementById("products-container");

products.forEach(product => {
    const box = document.createElement("div");
    box.classList.add("box");
    box.innerHTML = `
        <div class="image">
            <a href="product-details.html?id=${product.id}">
                <img src="${product.img}" alt="${product.name}">
            </a>
            <div class="icons">
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="cart-btn">Add to Cart</a>
            </div>
        </div>
        <div class="content">
            <h3>${product.name}</h3>
            <div class="price">${product.price}</div>
            <div class="stok">${product.stock}</div>
        </div>
    `;
    container.appendChild(box);
});

