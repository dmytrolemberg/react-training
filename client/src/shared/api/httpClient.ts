import { QueryClient } from '@tanstack/react-query';
import { CONFIG } from '@/shared/config/config.ts';

const JSON_CONTENT_TYPE = 'application/json';
const NO_CONTENT_STATUS = 204;

export interface RequestJsonOptions
  extends Omit<RequestInit, 'body'> {
  body?: unknown;
}

export class ApiError extends Error {
  readonly status: number;
  readonly details: unknown;

  constructor(status: number, statusText: string, details: unknown) {
    super(`${String(status)} ${statusText}`);
    this.name = 'ApiError';
    this.status = status;
    this.details = details;
  }
}

const buildUrl = (path: string): string => {
  const baseUrl = CONFIG.API_BASE_URL.endsWith('/')
    ? CONFIG.API_BASE_URL
    : `${CONFIG.API_BASE_URL}/`;

  return new URL(path.replace(/^\/+/, ''), baseUrl).toString();
};

const buildHeaders = (
  headersInit: HeadersInit | undefined,
): Headers => {
  const headers = new Headers(headersInit);
  headers.set('Accept', JSON_CONTENT_TYPE);
  return headers;
};

const readJson = async <ResponseData>(
  response: Response,
): Promise<ResponseData> => {
  if (response.status === NO_CONTENT_STATUS) {
    return undefined as ResponseData;
  }

  return response.json() as Promise<ResponseData>;
};

const readErrorDetails = async (response: Response): Promise<unknown> => {
  try {
    return (await response.json()) as unknown;
  } catch {
    return null;
  }
};

export const request = async <ResponseData>(
  path: string,
  options: RequestInit = {},
): Promise<ResponseData> => {
  const response = await fetch(buildUrl(path), {
    ...options,
    headers: buildHeaders(options.headers),
  });

  if (!response.ok) {
    throw new ApiError(
      response.status,
      response.statusText,
      await readErrorDetails(response),
    );
  }

  return readJson<ResponseData>(response);
};

export const requestJson = <ResponseData>(
  path: string,
  options: RequestJsonOptions = {},
): Promise<ResponseData> => {
  const { body, headers: headersInit, ...requestInit } = options;
  const hasBody = body !== undefined;
  const headers = buildHeaders(headersInit);

  if (hasBody && !headers.has('Content-Type')) {
    headers.set('Content-Type', JSON_CONTENT_TYPE);
  }

  return request<ResponseData>(path, {
    ...requestInit,
    headers,
    body: hasBody ? JSON.stringify(body) : undefined,
  });
};

export const queryClient = new QueryClient();
