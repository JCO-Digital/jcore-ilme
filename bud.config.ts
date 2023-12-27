import type {Bud} from '@roots/bud'

export default async (bud: Bud) => {
    bud.runtime(false)
    bud.entry({
        app: await bud.glob('./js/*.js'),
        theme: await bud.glob('./css/theme.css')
    })
}