<template>
    <div class="container">
        <h1>Metropolitan Museum Art Search</h1>
        <div v-if="!isLoading">
            <div class="searchBox">
                <div>
                    <label for="department">Department: </label>
                    <select v-model="selectedDepartment">
                        <option v-for="dept in departments" :key="dept.departmentId" :value="dept.departmentId">
                            {{ dept.displayName }}
                        </option>
                    </select>
                </div>
                <div>
                    <input v-model="query" placeholder="Search artwork title" @keyup.enter="searchArtworks" />
                    <button @click="searchArtworks">Search</button>
                </div>
            </div>
            <div v-if="!isDataLoading">
                <ul v-if="artworks.length" class="result-list">
                    <li v-for="art in artworks" :key="art.objectID">
                        <h3>{{ art.title }}</h3>
                        <p><strong>Date:</strong> {{ art.date }}</p>
                        <img class="artwork-image" :src="art.image" v-if="art.image" alt="Artwork image" />
                    </li>
                </ul>
                <div v-else class="empty-message">
                    {{ searchMessage }}
                </div>
            </div>
            <div class="loader" v-else>
                The data is loading ...
            </div>
        </div>
        <div class="loader" v-else>
            ... Loading ...
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            departments: [],
            selectedDepartment: null,
            query: '',
            artworks: [],
            isLoading: false,
            isDataLoading: false,
            searchMessage: ''
        };
    },
    async created() {
        this.isLoading = true;
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/departments');
            this.departments = response.data.departments;
        } catch (error) {
            console.error('Failed to load departments:', error);
            this.searchMessage = 'Error loading departments';
        } finally {
            this.isLoading = false;
        }
    },
    methods: {
        async searchArtworks() {
            if (!this.selectedDepartment || !this.query.trim()) return;
            this.isDataLoading = true;
            this.searchMessage = '';

            try {
                const response = await axios.get('http://127.0.0.1:8000/api/search', {
                    params: { departmentId: this.selectedDepartment, searchTerm: this.query }
                });

                if (response.data.length) {
                    this.artworks = response.data.map(art => ({
                        title: art.title,
                        artist: art.artist,
                        date: art.date,
                        image: art.image
                    }));
                } else {
                    this.searchMessage = 'No artwork found';
                }
            } catch (error) {
                console.error('Error fetching artworks:', error);
                this.searchMessage = 'Failed to fetch artworks. Please try again later.';
            } finally {
                this.isDataLoading = false;
            }
        }
    },
    watch: {
        selectedDepartment() {
            if (this.query) {
                this.searchArtworks();
            }
        }
    }
};
</script>

<style scoped>
@import '../style.css';

h1, label, button {
    color: #213547;
}
h1 {
    text-align: center;
}
button {
    margin: 0 8px;
}
.container {
    padding: 16px 10vw;
    background-color: #c7d5e1;
}
.searchBox{
    display: flex;
    justify-content: space-evenly;
}
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
}
.artwork-image {
    max-width: min(80vw, 500px);
}
.loader {
    padding: 16px;
    text-align: center;
}
.empty-message {
    padding: 16px 0;
    text-align: center;
}
@media screen and (max-width: 840px) {
    .container {
        padding: 8px;
    }
    .searchBox {
        flex-direction: column;
        align-items: center;
    }
    .searchBox div {
        margin: 16px 0;
    }
}
</style>
