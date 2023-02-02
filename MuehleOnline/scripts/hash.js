import { sha256 } from "js-sha256";

export function hash(string) {
    sha256(string);
    return sha256.create();
}