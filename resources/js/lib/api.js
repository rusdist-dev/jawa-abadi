/**
 * Pembungkus fetch + state pemuatan untuk Alpine.
 * Aturan: tidak memakai axios (lihat context.md).
 */

export class ApiError extends Error {
    constructor(message, status = 0, data = null) {
        super(message);
        this.name = 'ApiError';
        this.status = status;
        this.data = data;
    }
}

function toQuery(params = {}) {
    const query = new URLSearchParams();

    for (const [key, value] of Object.entries(params)) {
        if (value === null || value === undefined || value === '' || value === false) continue;

        if (Array.isArray(value)) {
            value.forEach((v) => query.append(`${key}[]`, v));
        } else {
            query.append(key, value === true ? '1' : value);
        }
    }

    return query.toString();
}

export async function api(path, { params, signal } = {}) {
    const qs = toQuery(params);
    const url = `/api${path}${qs ? `?${qs}` : ''}`;

    let response;
    try {
        response = await fetch(url, { headers: { Accept: 'application/json' }, signal });
    } catch (error) {
        if (error.name === 'AbortError') throw error;
        throw new ApiError('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    }

    let body = null;
    try {
        body = await response.json();
    } catch {
        // respons bukan JSON
    }

    if (!response.ok) {
        throw new ApiError(body?.message || 'Terjadi kesalahan pada server.', response.status, body);
    }

    return body;
}

/**
 * State pemuatan: idle | loading | success | error.
 * Pemanggilan baru membatalkan pemanggilan sebelumnya sehingga hasil usang tidak menimpa yang terbaru.
 */
export function createResource(fetcher) {
    let controller = null; // sengaja di luar objek agar tidak dibungkus proxy reaktif

    return {
        state: 'idle',
        data: null,
        error: null,

        get loading() {
            return this.state === 'loading';
        },
        get failed() {
            return this.state === 'error';
        },
        get loaded() {
            return this.state === 'success';
        },

        async load(...args) {
            controller?.abort();
            const current = (controller = new AbortController());

            this.state = 'loading';
            this.error = null;

            try {
                const data = await fetcher(...args, { signal: current.signal });
                if (current.signal.aborted) return undefined;

                this.data = data;
                this.state = 'success';
                return data;
            } catch (error) {
                if (error.name === 'AbortError' || current.signal.aborted) return undefined;

                this.error = error;
                this.state = 'error';
                return undefined;
            }
        },

        abort() {
            controller?.abort();
            if (this.state === 'loading') this.state = 'idle';
        },
    };
}

export const rupiah = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(value ?? 0);

export const number = (value) => new Intl.NumberFormat('id-ID').format(value ?? 0);
