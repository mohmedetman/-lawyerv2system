<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    display: flex;
    max-width: 1200px;
    margin: 0 auto;
}

aside.filters {
    width: 25%;
    padding: 20px;
    background-color: #fff;
    border-right: 1px solid #ddd;
}

aside.filters h2 {
    margin-top: 0;
}

.filter-group {
    margin-bottom: 15px;
}

.filter-group label {
    display: block;
    margin-bottom: 5px;
}

.filter-group select, .filter-group input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}

button {
    padding: 10px 15px;
    background-color: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

main.products {
    width: 75%;
    padding: 20px;
}

main.products h1 {
    margin-top: 0;
}

.product-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.product-item {
    width: calc(33.333% - 20px);
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 15px;
    box-sizing: border-box;
    text-align: center;
}

.product-item img {
    max-width: 100%;
    height: auto;
}

</style>
<body>
    <div class="container">
        <aside class="filters">
            <h2>Filter Products</h2>
            <form id="filterForm">
                <div class="filter-group">
                    <label for="brand">Brand:</label>
                    <select id="brand" name="brand">
                        <option value="">All</option>
                        <option value="brand1">Brand 1</option>
                        <option value="brand2">Brand 2</option>
                        <option value="brand3">Brand 3</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="minPrice">Min Price:</label>
                    <input type="number" id="minPrice" name="minPrice" placeholder="e.g., 20">
                </div>
                <div class="filter-group">
                    <label for="maxPrice">Max Price:</label>
                    <input type="number" id="maxPrice" name="maxPrice" placeholder="e.g., 100">
                </div>
                <div class="filter-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category">
                        <option value="">All</option>
                        <option value="category1">Category 1</option>
                        <option value="category2">Category 2</option>
                        <option value="category3">Category 3</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="sort">Sort By:</label>
                    <select id="sort" name="sort">
                        <option value="default">Default</option>
                        <option value="priceAsc">Price: Low to High</option>
                        <option value="priceDesc">Price: High to Low</option>
                        <option value="rating">Rating</option>
                    </select>
                </div>
                <button type="submit">Apply Filters</button>
            </form>
        </aside>
        <main class="products">
            <h1>Products</h1>
            <div class="product-list">
                <!-- Example Product Item -->
                <div class="product-item">
                    <img src="product1.jpg" alt="Product 1">
                    <h3>Product 1</h3>
                    <p>Brand: Brand 1</p>
                    <p>Price: $30</p>
                    <p>Category: Category 1</p>
                    <p>Rating: 4 Stars</p>
                </div>
                <!-- Add more product items as needed -->
            </div>
        </main>
    </div>
</body>
</html>
