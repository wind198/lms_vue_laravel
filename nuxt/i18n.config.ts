import { en } from "./lang/en";
import { vi } from "./lang/vi";

export type ITranslationGroup = Record<string, string>;

export type ITranslation = {
  entities: ITranslationGroup;
  actions: ITranslationGroup;
  messages: { info: ITranslationGroup; error: ITranslationGroup };
  others: ITranslationGroup;
};

export default defineI18nConfig(() => ({
  legacy: false,
  locale: "en",
  messages: {
    en,
    vi,
  },
}));
