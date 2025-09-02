import {useBaseAxios} from "./../utils/baseAxios";

export const useRecipeRepository = () => {
    const resource = "/api/recipes/";
    return {
        /**
         * 取得する
         */
        list() {
            return useBaseAxios(
                `${resource}`,
                {
                    method: "GET",
                },
                (response) => {
                    response.data
                },
            );
        },
    }
}
