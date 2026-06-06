<?php
session_start();
$isLoggedIn = isset($_COOKIE['user_id']);
$userName = $isLoggedIn ? $_COOKIE['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="eFgnt8h2jevnbNQc-VaQfAl3gnzpvkv0uBsoJBHphVo" />
    <title>Lumina | Fine Dining</title>
    <meta name="description" content="Lumina Fine Dining – Experience the art of culinary excellence. Reserve your table for an unforgettable evening.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../home.css">
</head>
<body>

    
    <header id="mainHeader">
        <div class="header-inner">
            <div class="logo">Lumina<span>.</span></div>
            <nav class="nav-links">
                <a href="#hero">Home</a>
                <a href="#gallery">Gallery</a>
                <a href="#menu-section">Menu</a>
                <a href="#contact">Contact</a>
            </nav>
            <div class="header-actions">
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle Theme">
                    <!-- Sun Icon -->
                    <svg class="sun-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <!-- Moon Icon -->
                    <svg class="moon-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>
                <button class="book-header-btn" onclick="openBooking()">Reserve a Table</button>
                
                <?php if ($isLoggedIn): ?>
                    <div class="user-profile">
                        <div class="user-avatar" onclick="document.getElementById('userMenu').classList.toggle('show')">
                            <?= strtoupper(substr($userName, 0, 1)) ?>
                        </div>
                        <div class="user-dropdown" id="userMenu">
                            <div class="ud-name"><?= htmlspecialchars($userName) ?></div>
                            <div class="ud-email">Guest</div>
                            <hr>
                            <a href="/profile" class="ud-logout" style="color:var(--text); margin-bottom:8px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                My Profile
                            </a>
                            <a href="/api/logout.php" class="ud-logout">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                Sign Out
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <button class="login-btn" id="loginBtn" onclick="window.location.href='index.html'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Sign In
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </header>

    
    <section class="hero" id="hero">
        <div class="hero-particles" id="heroParticles"></div>
        <div class="hero-content">
            <div class="hero-label">Est. 2010 · Michelin Starred</div>
            <h1 class="hero-title">
                <span class="title-line">An Exquisite</span>
                <span class="title-line gold">Dining Journey</span>
            </h1>
            <p class="hero-sub">Where culinary artistry meets timeless elegance.<br>Every plate tells a story.</p>
            <div class="hero-actions">
                <button class="btn-primary" onclick="openBooking()">
                    <span>Reserve Your Table</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
                <a href="#gallery" class="btn-ghost">
                    <span>Explore Atmosphere</span>
                </a>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-num">15+</div>
                    <div class="stat-label">Years of Excellence</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <div class="stat-num">3</div>
                    <div class="stat-label">Michelin Stars</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <div class="stat-num">50k+</div>
                    <div class="stat-label">Happy Guests</div>
                </div>
            </div>
        </div>
        <div class="hero-scroll-hint">
            <div class="scroll-line"></div>
            <span>Scroll</span>
        </div>
    </section>

    
    <section class="chef-section" id="about">
        <div class="chef-container">
            <div class="chef-image">
                <div class="chef-img-wrapper">
                    <!-- We use a placeholder image for the chef -->
                    <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Executive Chef">
                </div>
            </div>
            <div class="chef-content">
                <div class="section-tag">Executive Chef</div>
                <h2>"Cooking is an art, dining is an emotion."</h2>
                <p>At Lumina, we don't just serve food; we craft experiences. Every ingredient is handpicked, every technique is perfected, and every dish tells a story of passion and heritage.</p>
                <div class="chef-signature">
                    <!-- SVG Signature path -->
                    <svg viewBox="0 0 300 100" class="signature-svg">
                        <path class="signature-path" d="M30,70 C50,30 70,30 80,60 C90,80 110,40 120,40 C130,40 120,80 140,80 C160,80 150,20 170,20 C190,20 180,90 200,90 C220,90 230,50 250,50 C270,50 280,60 290,60" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <div class="chef-name">A. Lumière</div>
                </div>
            </div>
        </div>
    </section>

    <section class="gallery" id="gallery">
        <div class="section-header">
            <div class="section-tag">Visual Experience</div>
            <h2>Our Atmosphere</h2>
            <p>Immerse yourself in an ambiance crafted for unforgettable moments</p>
        </div>
        <div class="grid">
            <div class="image-box">
                <img src="../1.png" alt="Lumina Fine Dining Interior">
                <div class="image-overlay"><span>Main Dining Hall</span></div>
            </div>
            <div class="image-box">
                <img src="../2.png" alt="Lumina Terrace View">
                <div class="image-overlay"><span>Garden Terrace</span></div>
            </div>
            <div class="image-box">
                <img src="../3.png" alt="Lumina Signature Cuisine">
                <div class="image-overlay"><span>Signature Dishes</span></div>
            </div>
        </div>
    </section>

    
    <div id="bookingModal" class="modal" role="dialog" aria-modal="true" aria-label="Table Reservation">
        <div class="modal-backdrop" onclick="closeBooking()"></div>
        <div class="modal-panel">

            
            <div class="modal-deco">
                <div class="modal-deco-text">RESERVE</div>
                <div class="modal-deco-line"></div>
                <div class="modal-deco-stars">✦ ✦ ✦</div>
            </div>

            
            <div class="modal-body">
                <button class="modal-close" onclick="closeBooking()" aria-label="Close">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>

                <div class="modal-header">
                    <div class="modal-tag">Lumina Reservations</div>
                    <h2>Reserve Your Table</h2>
                    <p>Select your preferred date, time, and seating to begin your journey</p>
                </div>

                <form id="bookingForm" action="../api/booking.php" method="POST">

                    
                    <div class="booking-row">
                        <div class="booking-field">
                            <label for="booking-date">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                Date
                            </label>
                            <input type="date" id="booking-date" name="date" required>
                        </div>
                        <div class="booking-field">
                            <label for="booking-time">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                Time
                            </label>
                            <div class="select-wrap">
                                <select id="booking-time" name="time" required>
                                    <option value="">Select time</option>
                                    <optgroup label="🌅 Breakfast (6AM – 11AM)">
                                        <option value="06:00">06:00 AM – Sunrise Breakfast</option>
                                        <option value="07:00">07:00 AM</option>
                                        <option value="08:00">08:00 AM</option>
                                        <option value="09:00">09:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                    </optgroup>
                                    <optgroup label="☀️ Lunch (12PM – 5PM)">
                                        <option value="12:00">12:00 PM – Lunch</option>
                                        <option value="13:00">01:00 PM</option>
                                        <option value="14:00">02:00 PM</option>
                                        <option value="15:00">03:00 PM</option>
                                        <option value="16:00">04:00 PM</option>
                                        <option value="17:00">05:00 PM</option>
                                    </optgroup>
                                    <optgroup label="🌆 Dinner (6PM – Midnight)">
                                        <option value="18:00">06:00 PM – Golden Hour</option>
                                        <option value="19:00">07:00 PM – Sunset Special</option>
                                        <option value="20:00">08:00 PM – Twilight Dinner</option>
                                        <option value="21:00">09:00 PM</option>
                                        <option value="22:00">10:00 PM</option>
                                        <option value="23:00">11:00 PM – Late Night</option>
                                    </optgroup>
                                </select>
                                <svg class="sel-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </div>
                        </div>
                    </div>

                    
                    <div class="booking-field">
                        <label>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            Number of Guests
                        </label>
                        <div class="guest-counter">
                            <button type="button" class="counter-btn" id="guestMinus" onclick="changeGuests(-1)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            </button>
                            <div class="counter-display">
                                <span id="guestCount">2</span>
                                <span class="counter-label">guests</span>
                            </div>
                            <button type="button" class="counter-btn" id="guestPlus" onclick="changeGuests(1)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            </button>
                            <input type="hidden" id="guestInput" name="guests" value="2">
                        </div>
                    </div>

                    
                    <div class="booking-field">
                        <label>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            Preferred Seating
                        </label>
                        <div class="floor-plan-container" id="seatingCards">
                            <input type="hidden" id="seatInput" name="seating" value="Any Available">
                            <div class="floor-plan">
                                <div class="fp-entrance">Entrance & Bar</div>
                                
                                <div class="fp-zone fp-window">
                                    <div class="fp-zone-label">Window Side (City View)</div>
                                    <div class="fp-tables-row vertical">
                                        <div class="fp-table rect" onclick="selectMapTable(this, 'Window - W1')"><span>W1</span></div>
                                        <div class="fp-table rect" onclick="selectMapTable(this, 'Window - W2')"><span>W2</span></div>
                                        <div class="fp-table rect" onclick="selectMapTable(this, 'Window - W3')"><span>W3</span></div>
                                    </div>
                                </div>
                                
                                <div class="fp-zone fp-main">
                                    <div class="fp-zone-label">Main Dining Hall</div>
                                    <div class="fp-tables-grid">
                                        <div class="fp-table circle" onclick="selectMapTable(this, 'Main - M1')"><span>M1</span></div>
                                        <div class="fp-table circle" onclick="selectMapTable(this, 'Main - M2')"><span>M2</span></div>
                                        <div class="fp-table circle" onclick="selectMapTable(this, 'Main - M3')"><span>M3</span></div>
                                        <div class="fp-table circle" onclick="selectMapTable(this, 'Main - M4')"><span>M4</span></div>
                                        <div class="fp-table circle" onclick="selectMapTable(this, 'Main - M5')"><span>M5</span></div>
                                        <div class="fp-table circle" onclick="selectMapTable(this, 'Main - M6')"><span>M6</span></div>
                                    </div>
                                </div>
                                
                                <div class="fp-zone fp-vip">
                                    <div class="fp-zone-label">Private Rooms</div>
                                    <div class="fp-tables-col">
                                        <div class="fp-table private" onclick="selectMapTable(this, 'VIP - Room A')"><span>VIP A</span></div>
                                        <div class="fp-table private" onclick="selectMapTable(this, 'VIP - Room B')"><span>VIP B</span></div>
                                        <div class="fp-table private" onclick="selectMapTable(this, 'VIP - Room C')"><span>VIP C</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="fp-legend">
                                <div><span class="l-box avail"></span> Available</div>
                                <div><span class="l-box sel"></span> Selected</div>
                            </div>
                            <div class="selected-table-display">Selected Table: <span id="st-label">None</span></div>
                        </div>
                    </div>

                    
                    <div class="booking-field">
                        <label>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"></path><path d="M12 6v6l4 2"></path></svg>
                            Select Dishes <span class="optional">(optional)</span>
                        </label>
                        
                        <div class="menu-tabs">
                            <button type="button" class="menu-tab active" onclick="switchMenu('starters', this)">🥗 Starters</button>
                            <button type="button" class="menu-tab" onclick="switchMenu('mains', this)">🍽️ Mains</button>
                            <button type="button" class="menu-tab" onclick="switchMenu('desserts', this)">🍮 Desserts</button>
                            <button type="button" class="menu-tab" onclick="switchMenu('drinks', this)">🍷 Drinks</button>
                        </div>
                        
                        <div class="menu-category active" id="menu-starters">
                            <div class="menu-item" onclick="toggleMenuItem(this,'Bruschetta Trio')">
                                <div class="menu-item-emoji">🍅</div>
                                <div class="menu-item-info"><div class="menu-item-name">Bruschetta Trio</div><div class="menu-item-desc">Tomato, basil, ricotta</div></div>
                                <div class="menu-item-price">€12</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Beef Carpaccio')">
                                <div class="menu-item-emoji">🥩</div>
                                <div class="menu-item-info"><div class="menu-item-name">Beef Carpaccio</div><div class="menu-item-desc">Truffle oil, parmesan</div></div>
                                <div class="menu-item-price">€18</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Burrata Salad')">
                                <div class="menu-item-emoji">🧀</div>
                                <div class="menu-item-info"><div class="menu-item-name">Burrata Salad</div><div class="menu-item-desc">Heirloom tomatoes, pesto</div></div>
                                <div class="menu-item-price">€15</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Lobster Bisque')">
                                <div class="menu-item-emoji">🦞</div>
                                <div class="menu-item-info"><div class="menu-item-name">Lobster Bisque</div><div class="menu-item-desc">Cream, cognac, chives</div></div>
                                <div class="menu-item-price">€22</div>
                            </div>
                        </div>
                        
                        <div class="menu-category" id="menu-mains">
                            <div class="menu-item" onclick="toggleMenuItem(this,'Wagyu Ribeye')">
                                <div class="menu-item-emoji">🥩</div>
                                <div class="menu-item-info"><div class="menu-item-name">Wagyu Ribeye</div><div class="menu-item-desc">250g, truffle butter</div></div>
                                <div class="menu-item-price">€68</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Sea Bass en Papillote')">
                                <div class="menu-item-emoji">🐟</div>
                                <div class="menu-item-info"><div class="menu-item-name">Sea Bass</div><div class="menu-item-desc">En papillote, lemon caper</div></div>
                                <div class="menu-item-price">€42</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Duck Confit')">
                                <div class="menu-item-emoji">🦆</div>
                                <div class="menu-item-info"><div class="menu-item-name">Duck Confit</div><div class="menu-item-desc">Cherry jus, dauphinoise</div></div>
                                <div class="menu-item-price">€38</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Mushroom Risotto')">
                                <div class="menu-item-emoji">🍄</div>
                                <div class="menu-item-info"><div class="menu-item-name">Mushroom Risotto</div><div class="menu-item-desc">Porcini, parmesan, herbs</div></div>
                                <div class="menu-item-price">€28</div>
                            </div>
                        </div>
                        
                        <div class="menu-category" id="menu-desserts">
                            <div class="menu-item" onclick="toggleMenuItem(this,'Crème Brûlée')">
                                <div class="menu-item-emoji">🍮</div>
                                <div class="menu-item-info"><div class="menu-item-name">Crème Brûlée</div><div class="menu-item-desc">Vanilla, caramel crust</div></div>
                                <div class="menu-item-price">€14</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Chocolate Fondant')">
                                <div class="menu-item-emoji">🍫</div>
                                <div class="menu-item-info"><div class="menu-item-name">Chocolate Fondant</div><div class="menu-item-desc">Warm, vanilla ice cream</div></div>
                                <div class="menu-item-price">€16</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Tarte Tatin')">
                                <div class="menu-item-emoji">🥧</div>
                                <div class="menu-item-info"><div class="menu-item-name">Tarte Tatin</div><div class="menu-item-desc">Apple, caramel, cream</div></div>
                                <div class="menu-item-price">€13</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Cheese Board')">
                                <div class="menu-item-emoji">🧀</div>
                                <div class="menu-item-info"><div class="menu-item-name">Cheese Board</div><div class="menu-item-desc">Selection of 5 cheeses</div></div>
                                <div class="menu-item-price">€19</div>
                            </div>
                        </div>
                        
                        <div class="menu-category" id="menu-drinks">
                            <div class="menu-item" onclick="toggleMenuItem(this,'Champagne')">
                                <div class="menu-item-emoji">🥂</div>
                                <div class="menu-item-info"><div class="menu-item-name">Champagne</div><div class="menu-item-desc">Moët & Chandon Brut</div></div>
                                <div class="menu-item-price">€22</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Red Wine')">
                                <div class="menu-item-emoji">🍷</div>
                                <div class="menu-item-info"><div class="menu-item-name">Red Wine</div><div class="menu-item-desc">Bordeaux, Château Margaux</div></div>
                                <div class="menu-item-price">€18</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Mocktail')">
                                <div class="menu-item-emoji">🍹</div>
                                <div class="menu-item-info"><div class="menu-item-name">Signature Mocktail</div><div class="menu-item-desc">Seasonal, non-alcoholic</div></div>
                                <div class="menu-item-price">€10</div>
                            </div>
                            <div class="menu-item" onclick="toggleMenuItem(this,'Coffee')">
                                <div class="menu-item-emoji">☕</div>
                                <div class="menu-item-info"><div class="menu-item-name">Specialty Coffee</div><div class="menu-item-desc">Espresso, cappuccino…</div></div>
                                <div class="menu-item-price">€6</div>
                            </div>
                        </div>
                        <input type="hidden" id="menuInput" name="menu_items" value="">
                    </div>

                    
                    <div class="booking-field">
                        <label>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                            Special Requests <span class="optional">(optional)</span>
                        </label>
                        <textarea name="requests" placeholder="Allergies, anniversaries, dietary preferences…" rows="2"></textarea>
                    </div>

                    
                    <button type="submit" class="confirm-btn" id="confirmBtn">
                        <span>Confirm Reservation</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </button>

                    <p class="booking-note">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        Free cancellation up to 24 hours before your reservation
                    </p>
                </form>
            </div>
        </div>
    </div>

    
    <section class="menu-section" id="menu-section">
        <div class="section-header">
            <div class="section-tag">Culinary Excellence</div>
            <h2>Our Menu</h2>
            <p>Seasonal ingredients, timeless recipes, unforgettable flavours</p>
        </div>
        
        <div class="menu-section-tabs">
            <button class="ms-tab active" onclick="switchMenuSection('appetizers',this)">🥗 Appetizers</button>
            <button class="ms-tab" onclick="switchMenuSection('mains',this)">🍽️ Main Course</button>
            <button class="ms-tab" onclick="switchMenuSection('desserts',this)">🍮 Desserts</button>
            <button class="ms-tab" onclick="switchMenuSection('drinks',this)">🍷 Drinks</button>
        </div>

        
        <div class="ms-category active" id="ms-appetizers">
            <div class="menu-cards-grid">
                <div class="menu-card"><div class="mc-emoji">🍅</div><div class="mc-body"><div class="mc-name">Bruschetta Trio</div><div class="mc-desc">Toasted sourdough with heirloom tomato, ricotta and fresh basil</div></div><div class="mc-price">€12</div></div>
                <div class="menu-card"><div class="mc-emoji">🥩</div><div class="mc-body"><div class="mc-name">Beef Carpaccio</div><div class="mc-desc">Thinly sliced wagyu, truffle oil, shaved parmesan, capers</div></div><div class="mc-price">€18</div></div>
                <div class="menu-card"><div class="mc-emoji">🧀</div><div class="mc-body"><div class="mc-name">Burrata Salad</div><div class="mc-desc">Fresh burrata, heirloom tomatoes, pesto, aged balsamic</div></div><div class="mc-price">€15</div></div>
                <div class="menu-card"><div class="mc-emoji">🦞</div><div class="mc-body"><div class="mc-name">Lobster Bisque</div><div class="mc-desc">Velvety bisque with cognac cream, chives and lobster chunks</div></div><div class="mc-price">€22</div></div>
                <div class="menu-card"><div class="mc-emoji">🫙</div><div class="mc-body"><div class="mc-name">Foie Gras Terrine</div><div class="mc-desc">Duck foie gras, brioche toast, fig compote, fleur de sel</div></div><div class="mc-price">€26</div></div>
                <div class="menu-card"><div class="mc-emoji">🐚</div><div class="mc-body"><div class="mc-name">Scallop Ceviche</div><div class="mc-desc">Hand-dived scallops, yuzu, cucumber, micro herbs</div></div><div class="mc-price">€20</div></div>
            </div>
        </div>

        
        <div class="ms-category" id="ms-mains">
            <div class="menu-cards-grid">
                <div class="menu-card featured"><div class="mc-badge">Chef's Choice</div><div class="mc-emoji">🥩</div><div class="mc-body"><div class="mc-name">Wagyu Ribeye</div><div class="mc-desc">250g A5 Wagyu, truffle butter, confit garlic, seasonal vegetables</div></div><div class="mc-price">€68</div></div>
                <div class="menu-card"><div class="mc-emoji">🐟</div><div class="mc-body"><div class="mc-name">Sea Bass en Papillote</div><div class="mc-desc">Line-caught sea bass, lemon caper butter, fennel, dill</div></div><div class="mc-price">€42</div></div>
                <div class="menu-card"><div class="mc-emoji">🦆</div><div class="mc-body"><div class="mc-name">Duck Confit</div><div class="mc-desc">Slow-cooked duck leg, cherry jus, dauphinoise potatoes</div></div><div class="mc-price">€38</div></div>
                <div class="menu-card"><div class="mc-emoji">🍄</div><div class="mc-body"><div class="mc-name">Mushroom Risotto</div><div class="mc-desc">Porcini, parmesan reggiano, truffle oil, fresh herbs</div></div><div class="mc-price">€28</div></div>
                <div class="menu-card"><div class="mc-emoji">🐑</div><div class="mc-body"><div class="mc-name">Rack of Lamb</div><div class="mc-desc">French-trimmed, herb crust, mint jus, ratatouille</div></div><div class="mc-price">€52</div></div>
                <div class="menu-card"><div class="mc-emoji">🦐</div><div class="mc-body"><div class="mc-name">Tiger Prawns</div><div class="mc-desc">Grilled tiger prawns, garlic butter, chilli, linguine</div></div><div class="mc-price">€44</div></div>
            </div>
        </div>

        
        <div class="ms-category" id="ms-desserts">
            <div class="menu-cards-grid">
                <div class="menu-card featured"><div class="mc-badge">Signature</div><div class="mc-emoji">🍮</div><div class="mc-body"><div class="mc-name">Crème Brûlée</div><div class="mc-desc">Classic vanilla bean custard with caramelised sugar crust</div></div><div class="mc-price">€14</div></div>
                <div class="menu-card"><div class="mc-emoji">🍫</div><div class="mc-body"><div class="mc-name">Chocolate Fondant</div><div class="mc-desc">Warm dark chocolate with molten centre, vanilla ice cream</div></div><div class="mc-price">€16</div></div>
                <div class="menu-card"><div class="mc-emoji">🥧</div><div class="mc-body"><div class="mc-name">Tarte Tatin</div><div class="mc-desc">Caramelised apple tart, crème fraîche, salted caramel</div></div><div class="mc-price">€13</div></div>
                <div class="menu-card"><div class="mc-emoji">🧀</div><div class="mc-body"><div class="mc-name">Artisan Cheese Board</div><div class="mc-desc">Selection of 5 fine cheeses, grapes, honey, walnuts</div></div><div class="mc-price">€19</div></div>
                <div class="menu-card"><div class="mc-emoji">🍓</div><div class="mc-body"><div class="mc-name">Strawberry Pavlova</div><div class="mc-desc">Meringue, chantilly cream, fresh strawberries, mint</div></div><div class="mc-price">€12</div></div>
                <div class="menu-card"><div class="mc-emoji">🍨</div><div class="mc-body"><div class="mc-name">Sorbet Trio</div><div class="mc-desc">Seasonal fruit sorbets, almond tuile, berry coulis</div></div><div class="mc-price">€10</div></div>
            </div>
        </div>

        
        <div class="ms-category" id="ms-drinks">
            <div class="menu-cards-grid">
                <div class="menu-card featured"><div class="mc-badge">Sommelier's Pick</div><div class="mc-emoji">🥂</div><div class="mc-body"><div class="mc-name">Champagne</div><div class="mc-desc">Moët &amp; Chandon Brut Impérial, perfectly chilled</div></div><div class="mc-price">€22</div></div>
                <div class="menu-card"><div class="mc-emoji">🍷</div><div class="mc-body"><div class="mc-name">Bordeaux Rouge</div><div class="mc-desc">Château Margaux 2018, rich tannins, cherry and cedar notes</div></div><div class="mc-price">€18</div></div>
                <div class="menu-card"><div class="mc-emoji">🍸</div><div class="mc-body"><div class="mc-name">Lumina Signature Cocktail</div><div class="mc-desc">Gold-infused gin, elderflower, tonic, edible gold leaf</div></div><div class="mc-price">€16</div></div>
                <div class="menu-card"><div class="mc-emoji">🍹</div><div class="mc-body"><div class="mc-name">Seasonal Mocktail</div><div class="mc-desc">Rotating seasonal blend, premium syrups, fresh herbs</div></div><div class="mc-price">€10</div></div>
                <div class="menu-card"><div class="mc-emoji">☕</div><div class="mc-body"><div class="mc-name">Specialty Coffee</div><div class="mc-desc">Single-origin espresso, cappuccino, cortado or Americano</div></div><div class="mc-price">€6</div></div>
                <div class="menu-card"><div class="mc-emoji">🍵</div><div class="mc-body"><div class="mc-name">Herbal Infusion</div><div class="mc-desc">Curated selection of fine loose-leaf teas and herbal blends</div></div><div class="mc-price">€5</div></div>
            </div>
        </div>
    </section>

    
    <section class="testimonials">
        <div class="section-header">
            <div class="section-tag">Words of Praise</div>
            <h2>Guest Experiences</h2>
        </div>
        <div class="marquee-container">
            <div class="marquee-content">
                <!-- Repeat cards to ensure smooth infinite scroll -->
                <div class="t-card">
                    <div class="t-stars">★★★★★</div>
                    <p class="t-text">"An absolute masterpiece of culinary art. The truffle risotto was divine, and the atmosphere is unmatched."</p>
                    <div class="t-author">- Vedat M., Food Critic</div>
                </div>
                <div class="t-card">
                    <div class="t-stars">★★★★★</div>
                    <p class="t-text">"The private dining room provided the perfect setting for our anniversary. Exceptional service!"</p>
                    <div class="t-author">- Sarah & James</div>
                </div>
                <div class="t-card">
                    <div class="t-stars">★★★★★</div>
                    <p class="t-text">"Every dish tells a story. You can taste the passion of the chef in every bite. Will definitely return."</p>
                    <div class="t-author">- Chef Gordon</div>
                </div>
                <!-- Clones for infinite loop -->
                <div class="t-card">
                    <div class="t-stars">★★★★★</div>
                    <p class="t-text">"An absolute masterpiece of culinary art. The truffle risotto was divine, and the atmosphere is unmatched."</p>
                    <div class="t-author">- Vedat M., Food Critic</div>
                </div>
                <div class="t-card">
                    <div class="t-stars">★★★★★</div>
                    <p class="t-text">"The private dining room provided the perfect setting for our anniversary. Exceptional service!"</p>
                    <div class="t-author">- Sarah & James</div>
                </div>
                <div class="t-card">
                    <div class="t-stars">★★★★★</div>
                    <p class="t-text">"Every dish tells a story. You can taste the passion of the chef in every bite. Will definitely return."</p>
                    <div class="t-author">- Chef Gordon</div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-section" id="contact">
        <div class="section-header">
            <div class="section-tag">Get In Touch</div>
            <h2>Contact Us</h2>
            <p>We'd love to hear from you — for reservations, events or enquiries</p>
        </div>
        <div class="contact-grid">
            <div class="contact-card">
                <div class="contact-icon">📍</div>
                <div class="contact-label">Address</div>
                <div class="contact-value">14 Rue de la Lumière, 75008 Paris, France</div>
            </div>
            <div class="contact-card">
                <div class="contact-icon">📞</div>
                <div class="contact-label">Reservations</div>
                <div class="contact-value">+33 (0)1 47 83 92 10</div>
                <div class="contact-sub">Mon–Sun · 10:00 – 22:00</div>
            </div>
            <div class="contact-card">
                <div class="contact-icon">✉️</div>
                <div class="contact-label">Email</div>
                <div class="contact-value">reservations@lumina-dining.com</div>
                <div class="contact-sub">We reply within 24 hours</div>
            </div>
        </div>
    </section>

    
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="logo">Lumina<span>.</span></div>
                <p>Elevating fine dining to an art form. Join us for a culinary journey you will never forget.</p>
                <div class="social-links">
                    <a href="#"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
                    <a href="#"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
                    <a href="#"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg></a>
                </div>
            </div>
            <div class="footer-links">
                <h3>Quick Links</h3>
                <a href="#about">Our Story</a>
                <a href="#menu">Menu</a>
                <a href="#gallery">Gallery</a>
                <a href="admin_login.html">Staff Portal</a>
            </div>
            <div class="footer-newsletter">
                <h3>Newsletter</h3>
                <p>Subscribe for exclusive invites and seasonal menu updates.</p>
                <form action="../api/newsletter.php" method="POST" class="nl-form">
                    <input type="email" name="nl_email" placeholder="Email address" required>
                    <button type="submit">→</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Lumina Fine Dining. All rights reserved.</p>
        </div>
    </footer>



    
    <div class="toast" id="successToast">
        <div class="toast-icon">✦</div>
        <div class="toast-text">
            <strong>Reservation Confirmed!</strong>
            <span>We look forward to welcoming you.</span>
        </div>
    </div>

    <script src="../home.js"></script>
</body>
</html>
