<template>
    <div>
        <div class="error-wrapper">
            <div class="error-content">

                <h1 class="error-code">404</h1>
                <h1 class="error-message">The page you are looking not found.</h1>

                <router-link to="/" class="btn btn-dark error-button">
                    Bring me back Home
                </router-link>
            </div>
        </div>
    </div>
</template>

<style>
    .error-wrapper {
        display: flex;
        min-height: calc(100vh - 200px);
    }

    .error-content {
        margin: auto;
        text-align: center;
    }

    .error-code {
        font-size: 250px;
    }

    .error-message {
        font-size: 50px;
        margin-bottom: 50px;
    }

    .error-button {
        font-size: 30px;
    }
</style>