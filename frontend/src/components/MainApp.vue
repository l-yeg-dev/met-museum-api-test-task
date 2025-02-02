<template>
    <div class="container">
        <h1>Metropolitan Museum Art Search</h1>
        <ArtSearch
            :departments="departments"
            :selectedDepartment="selectedDepartment"
            :query="query"
            @search="handleSearch"
        />
        <ArtworksList
            :artworks="artworks"
            :isDataLoading="isDataLoading"
            :searchMessage="searchMessage"
        />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import apiService from '../services/apiService';
import ArtSearch from '../components/ArtSearch.vue';
import ArtworksList from '../components/ArtworksList.vue';

export default {
    components: { ArtSearch, ArtworksList },
    setup() {
        const departments = ref([]);
        const selectedDepartment = ref(null);
        const query = ref('');
        const artworks = ref([]);
        const isLoading = ref(false);
        const isDataLoading = ref(false);
        const searchMessage = ref('');

        // Fetch departments when component mounts
        onMounted(async () => {
            isLoading.value = true;
            try {
                const data = await apiService.getDepartments();
                departments.value = data.departments;
            } catch (error) {
                searchMessage.value = 'Error loading departments';
            } finally {
                isLoading.value = false;
            }
        });

        // Handle search event from ArtSearch component
        const handleSearch = async ({ departmentId, query: searchQuery }) => {
            if (!departmentId || !searchQuery.trim()) return;
            isDataLoading.value = true;
            searchMessage.value = '';

            try {
                const data = await apiService.searchArtworks(departmentId, searchQuery);
                artworks.value = data.length
                    ? data.map(art => ({
                        title: art.title,
                        artist: art.artist,
                        date: art.date,
                        image: art.image
                    }))
                    : [];

                if (!data.length) {
                    searchMessage.value = 'No artwork found';
                }
            } catch (error) {
                searchMessage.value = 'Failed to fetch artworks. Please try again later.';
            } finally {
                isDataLoading.value = false;
            }
        };

        return {
            departments,
            selectedDepartment,
            query,
            artworks,
            isLoading,
            isDataLoading,
            searchMessage,
            handleSearch
        };
    }
};
</script>

<style scoped>

h1 {
    text-align: center;
}
.container {
    padding: 16px 10vw;
    background-color: #c7d5e1;
}
</style>
