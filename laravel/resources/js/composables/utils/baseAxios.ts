import axios, {AxiosRequestConfig, AxiosResponse, AxiosError} from "axios";

type ErrorResponse = {
    message?: string;
    errors?: Record<string, string[]>;
};

/**
 * Axios をラップした API 呼び出し関数
 * @param url API のエンドポイント
 * @param options AxiosRequestConfig
 * @param onSuccess 成功時のコールバック関数（任意）
 * @param onError 失敗時のコールバック関数（任意）
 * @returns API のレスポンスデータ or null
 */

export const useBaseAxios = async (
    url: string,
    options?: AxiosRequestConfig,
    onSuccess?: (response: AxiosResponse) => void,
    onError?: (error: AxiosError<ErrorResponse>) => void,
): Promise<
    { ok: true; data: unknown } | { ok: false; error: AxiosError<ErrorResponse> }
> => {
    try {
        const response = await axios(url, options);
        onSuccess?.(response);
        return {ok: true, data: response.data};
    } catch (error) {
        onError?.(error as AxiosError<ErrorResponse>);
        return {ok: false, error: error as AxiosError<ErrorResponse>};
    }
};
