<template>
    <div v-if="!isDataLoading">
        <ul v-if="artworks.length" class="result-list">
            <li v-for="art in artworks" :key="art.title">
                <h3>{{ art.title }}</h3>
                <p><strong>Date:</strong> {{ art.date }}</p>
                <img class="artwork-image" v-if="art.image" :src="art.image" alt="Artwork image" />
            </li>
        </ul>
        <div v-else class="empty-message">
            {{ searchMessage }}
        </div>
    </div>
    <div class="loader" v-else>
        The data is loading ...
    </div>
</template>

<script>
export default {
    props: {
        artworks: Array,
        isDataLoading: Boolean,
        searchMessage: String
    }
};
</script>

<style scoped>
.result-list {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.result-list li {
    max-width: 550px;
    min-width: 60vw;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: aliceblue;
    margin: 4px 0;
}
.artwork-image {
    max-width: min(80vw, 500px);
}
.loader, .empty-message {
    padding: 16px;
    text-align: center;
}
</style>
