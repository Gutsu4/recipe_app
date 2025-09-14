import {useBaseAxios} from "./../utils/baseAxios";

export const useRecipeRepository = () => {
    const resource = "/api/recipes/";
    return {
        /**
         * 取得する
         */
        list(orderBy?: string, order?: string) {
            const params = new URLSearchParams();
            if (orderBy) params.append('OrderBy', orderBy);
            if (order) params.append('Order', order);
            
            const queryString = params.toString();
            const url = queryString ? `${resource}?${queryString}` : resource;
            
            return useBaseAxios(
                url,
                {
                    method: "GET",
                },
                (response) => {
                    response.data
                },
            );
        },
        /**
         * 詳細を取得する
         */
        show(id: number) {
            return useBaseAxios(
                `${resource}${id}`,
                {
                    method: "GET",
                },
                (response) => {
                    return response.data;
                },
            );
        },
        /**
         * 登録する
         */
        store(data: any) {
            return useBaseAxios(
                `${resource}`,
                {
                    method: "POST",
                    data: data,
                },
                (response) => {
                    response.data
                },
            );
        },
        /**
         * 更新する
         */
        update(id: number, data: any) {
            return useBaseAxios(
                `${resource}${id}`,
                {
                    method: "PUT",
                    data: data,
                },
                (response) => {
                    response.data
                },
            );
        },
        /**
         * 削除する
         */
        delete(id: number) {
            return useBaseAxios(
                `${resource}${id}`,
                {
                    method: "DELETE",
                },
                (response) => {
                    return response.data;
                },
            );
        },
    }
}
