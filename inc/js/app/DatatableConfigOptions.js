/**
 * Standard Format : Use CamelCase for the function name and the file name
 *
 * getBaseURL
 * @param url
 * @returns {`${string}//${string}`}
 */
export const getBaseUrl = function(url) {
    const parsedUrl = new URL(url);
    return `${parsedUrl.protocol}//${parsedUrl.hostname}`;
}

/**
 * getPreselectOptions
 * @param column
 * @param rows
 * @returns {[{column, rows: *[]}]}
 */
export const getPreselectOptions = function(column, rows = []) {
    return [{
        column,
        rows,
    }];
}