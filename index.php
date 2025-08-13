<?php include 'header.php'; ?>

<main class="home">
    <div class="home-intro">
        <h1>DELISH-BITE</h1>
        <p>Welcome to Delish-Bite, your go-to destination for delicious food and delightful experiences. Explore our menu, place your order, and enjoy the best culinary delights in town!</p>
    </div>
    <section id="cart" class="cart-container">
        <h2>
            <i class="fas fa-shopping-cart"></i> Your Cart
        </h2>
        <ul id="cart-items" class="cart-items">
            <li class="cart-empty-msg">Your cart is empty</li>
        </ul>
        <div class="cart-total">
            <strong>Total: Ksh <span id="cart-total-price">0</span></strong>
        </div>
        </section>
<section class="menu-container">
    <h2>Our Menu</h2>

    <?php
    // 1. Include the database connection file
    include 'db_connect.php';

    // 2. Prepare the SQL query to get all menu items
    $sql = "SELECT id, name, price, image_path, alt_text FROM menu_items";
    $result = $conn->query($sql);
    ?>

    <ul class="menu-grid">
        <?php
        // 3. Check if we got any results from the database
        if ($result->num_rows > 0):
            // 4. Loop through each row of the results
            while($item = $result->fetch_assoc()):
        ?>
                <li class="menu-card">
                    <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['alt_text']; ?>">
                    <div class="card-content">
                        <h3 class="food-name"><?php echo $item['name']; ?></h3>
                        <p class="food-price">KSh <?php echo $item['price']; ?></p>
                        <button class="add-to-cart-btn">Add to Cart</button>
                    </div>
                </li>
        <?php
            endwhile;
        else:
            // This message will show if the table is empty
            echo "<p>No menu items found.</p>";
        endif;

        // 5. Close the database connection
        $conn->close();
        ?>
    </ul>
</section>
</main>

<?php include 'footer.php'; ?>