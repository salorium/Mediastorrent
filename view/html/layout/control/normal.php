<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 20/03/14
 * Time: 15:53
 */
?>

<nav class="top-bar" data-topbar="">
    <!-- Title -->
    <ul class="title-area">
        <li class="name"><h1><a href="#">Sexy Top Bar</a></h1></li>

        <!-- Mobile Menu Toggle -->
        <li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
    </ul>

    <!-- Top Bar Section -->

    <section class="top-bar-section">

        <!-- Top Bar Left Nav Elements -->
        <ul class="left">

            <!-- Search | has-form wrapper -->
            <li class="has-form">
                <div class="row collapse">
                    <div class="large-8 small-9 columns">
                        <input placeholder="Find Stuff" type="text">
                    </div>
                    <div class="large-4 small-3 columns">
                        <a href="#" class="alert button expand">Search</a>
                    </div>
                </div>
            </li>
            <li class="has-form">
                <a class="button">Test</a>
            </li>
        </ul>

        <!-- Top Bar Right Nav Elements -->
        <ul class="right">
            <!-- Divider -->
            <li class="divider"></li>

            <!-- Dropdown -->
            <li class="has-dropdown not-click"><a href="http://zurb.com">Item 1</a>
                <ul class="dropdown">
                    <li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li>
                    <li><label>Level One</label></li>
                    <li><a href="#">Sub-item 1</a></li>
                    <li><a href="#">Sub-item 2</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Sub-item 3</a></li>
                    <li class="has-dropdown not-click"><a href="#">Sub-item 4</a>

                        <!-- Nested Dropdown -->
                        <ul class="dropdown">
                            <li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li>
                            <li><label>Level Two</label></li>
                            <li><a href="#">Sub-item 2</a></li>
                            <li><a href="#">Sub-item 3</a></li>
                            <li><a href="#">Sub-item 4</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Sub-item 5</a></li>
                </ul>
            </li>

            <li class="divider"></li>

            <!-- Anchor -->
            <li><a href="#">Generic Button</a></li>
            <li class="divider"></li>

            <!-- Button -->
            <li class="has-form show-for-large-up">
                <a href="http://foundation.zurb.com/docs" class="button">Get Lucky</a>
            </li>
        </ul>
    </section>
</nav>