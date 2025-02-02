import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:8000/api';

const apiService = {
    /**
     * Fetch all available departments.
     * @returns {Promise<Array>} Array of departments.
     */
    async getDepartments() {
        try {
            const response = await axios.get(`${API_BASE_URL}/departments`);
            return response.data;
        } catch (error) {
            console.error('Failed to load departments:', error);
            throw new Error('Error fetching departments');
        }
    },

    /**
     * Search for artworks in a specific department.
     * @param {number} departmentId - The department ID.
     * @param {string} searchTerm - The search term for artworks.
     * @returns {Promise<Array>} Array of artworks.
     */
    async searchArtworks(departmentId, searchTerm) {
        try {
            const response = await axios.get(`${API_BASE_URL}/search`, {
                params: { departmentId, searchTerm }
            });
            return response.data;
        } catch (error) {
            console.error('Search failed:', error);
            throw new Error('Error fetching artworks');
        }
    },

    /**
     * Fetch details of a specific artwork.
     * @param {number} artworkId - The artwork ID.
     * @returns {Promise<Object>} Artwork details.
     */
    async getArtworkDetails(artworkId) {
        try {
            const response = await axios.get(`${API_BASE_URL}/object/${artworkId}`);
            return response.data;
        } catch (error) {
            console.error(`Failed to fetch artwork ${artworkId}:`, error);
            throw new Error('Error fetching artwork details');
        }
    }
};

export default apiService;
