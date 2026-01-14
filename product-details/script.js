const products = [
    {
        id: 1,
        name: "COSRX Snail Mucin",
        price: "2000 L",
        img: "https://m.media-amazon.com/images/I/51IF5kpotSL.jpg",
        desc: "COSRX Snail Mucin products (especially the Advanced Snail 96 Mucin Power Essence) are Korean‑beauty skincare formulas centered around snail secretion filtrate—commonly called snail mucin. In COSRX’s essence, this ingredient makes up around 96 % of the product, giving it a high concentration compared to many others on the market.\n" +
            "\n" +
            "Snail mucin itself is a natural secretion rich in moisturizing and repair‑friendly compounds such as hyaluronic acid, glycoproteins, allantoin, proteins, and peptides.\n" +
            "\n" +
            "The mucin is collected in a way that’s designed to be gentle and cruelty‑free, with no harm to the snails.",
        ingredients: ["Snail Secretion Filtrate", "Hyaluronic Acid", "Allantoin & Panthenol", "Betaine"]
    },

    {
        id: 2,
        name: "Innisfree Cleanser",
        price: "1900 L",
        img: "https://m.media-amazon.com/images/I/61KeAJa4x6S._SL1500_.jpg",
        desc: "Innisfree is a Korean beauty brand known for using naturally derived ingredients from Jeju Island (e.g., green tea, volcanic ash, tangerine extracts) to create gentle yet effective skincare products. Their face cleansers are typically foam cleansers or gel cleansers formulated to wash away dirt, oil, sebum, and makeup residue without excessively drying the skin.\n" +
            "\n" +
            "Most products are alcohol‑free (though formulations can vary), and many are designed for everyday use — morning and night.",
        ingredients: ["Jeju Green Tea Water", "Triple Amino Acid Complex", " Mild surfactants"]
    },

    {
        id: 3,
        name: "Relief Sun SPF 50",
        price: "1700 L",
        img: "https://m.media-amazon.com/images/I/41ZNRwLuTRL.jpg",
        desc: "Beauty of Joseon Relief Sun : Rice + Probiotics SPF 50+ PA++++ is a K‑beauty daily sunscreen formulated to provide broad‑spectrum protection against UVA and UVB rays with an SPF 50+ and highest PA++++ rating. It’s designed to feel like a lightweight moisturizing cream rather than a heavy sunscreen, making it comfortable for everyday wear.",
        ingredients: ["Oryza Sativa (Rice) Extract (~30%)", "Niacinamide", "Fermented Extracts", "Rice Germ Extract"]
    },

    {
        id: 4,
        name: "Innisfree Green Tea Moisturizer",
        price: "1800 L",
        img: "https://i.pinimg.com/originals/96/3f/b5/963fb56dfb98e67ecceff5f7532ac546.jpg",
        desc: "This is a daily face moisturizer formulated to deeply hydrate and nourish skin while strengthening its moisture barrier. It’s part of Innisfree’s Green Tea line, which uses green tea grown on Jeju Island known for rich amino acids and antioxidants. The texture is lightweight yet rich in hydration, making it suitable for all skin types including dry, combination, and sensitive skin.",
        ingredients: ["Green Tea (Camellia Sinensis) Extract", "Green Tea Seed Oil", "Hyaluronic Acid", "Panthenol (Vitamin B5)"]
    },

    {
        id: 5,
        name: "Anua Heartleaf Soothing Serum",
        price: "2200 L",
        img: "https://tse1.mm.bing.net/th/id/OIP.kxiyxj_whClQW3sxvvfpkQHaHa?rs=1&pid=ImgDetMain&o=7&rm=3",
        desc: "Anua Heartleaf 80% Soothing Ampoule is a lightweight, serum‑like ampoule designed to soothe irritation, calm redness, and deeply hydrate the skin. It’s especially popular for sensitive, redness‑prone, and reactive skin types, but is suitable for all skin types. The formula emphasizes a high concentration (≈80%) of Heartleaf extract (Houttuynia cordata) — a plant‑derived ingredient acclaimed in K‑beauty for anti‑inflammatory and calming effects.",
        ingredients: ["Houttuynia Cordata Extract (≈80%)", "Glycerin", "Sodium Hyaluronate", "Panthenol (Vitamin B5)"]
    },

    {
        id: 6,
        name: "Beauty of Joseon Glow Mask",
        price: "900 L",
        img: "https://tse3.mm.bing.net/th/id/OIP.ucM_737MyVqdA1sBPbwezAHaGR?rs=1&pid=ImgDetMain&o=7&rm=3\" alt=\"Face Mask",
        desc: "This is a multi‑benefit wash‑off mask designed to brighten, exfoliate, hydrate, and refine the skin’s texture in one step. Inspired by traditional Korean skincare rituals using rice and honey, the formula blends gentle exfoliants with soothing and moisturizing ingredients. It’s formulated to be gentle enough for all skin types, including sensitive skin, and leaves the complexion feeling soft, refreshed, and glowing without drying it out.",
        ingredients: ["Rice Wine Complex (Makgeolli Extract)", "Rice Hull Powder", "Honey (≈5%)", "Kaolin Clay"]
    },







];

const params = new URLSearchParams(window.location.search);
const productId = parseInt(params.get("id"));

const product = products.find(p => p.id === productId);

if (product) {
    document.getElementById("product-img").src = product.img;
    document.getElementById("product-name").textContent = product.name;
    document.getElementById("product-price").textContent = product.price;
    document.getElementById("product-desc").textContent = product.desc;

    const ul = document.getElementById("product-ingredients");
    product.ingredients.forEach(item => {
        const li = document.createElement("li");
        li.textContent = item;
        ul.appendChild(li);
    });
}

let quantity = 1;

const qtyEl = document.getElementById("qty");
const plus = document.getElementById("plus");
const minus = document.getElementById("minus");
const favBtn = document.getElementById("fav");

favBtn.addEventListener("click", () => {
    favBtn.classList.toggle("active");

    if (favBtn.classList.contains("active")) {
        favBtn.classList.remove("far");
        favBtn.classList.add("fas");
    } else {
        favBtn.classList.remove("fas");
        favBtn.classList.add("far");
    }
});





plus.addEventListener("click", () => {
    quantity++;
    qtyEl.textContent = quantity;
});

minus.addEventListener("click", () => {
    if (quantity > 1) {
        quantity--;
        qtyEl.textContent = quantity;
    }
});

const desc = document.getElementById("product-desc");
const toggleBtn = document.getElementById("toggle-desc");

toggleBtn.addEventListener("click", () => {
    desc.classList.toggle("collapsed");

    toggleBtn.textContent = desc.classList.contains("collapsed")
        ? "See more"
        : "See less";
});

