const { registerBlockType } = wp.blocks;
const { TextControl, CheckboxControl } = wp.components;
const { InspectorControls } = wp.blockEditor;

registerBlockType("custom-plugin/film-block", {
  title: "Bloc Film",
  icon: "video-alt2",
  category: "widgets",
  attributes: {
    filmId: {
      type: "number",
      default: 0,
    },
    displayLatest: {
      type: "boolean",
      default: true,
    },
  },
  edit: ({ attributes, setAttributes }) => {
    const { filmId, displayLatest } = attributes;

    return (
      <>
        <InspectorControls>
          <CheckboxControl
            label="Afficher les derniers films"
            checked={displayLatest}
            onChange={(value) => setAttributes({ displayLatest: value })}
          />
          {!displayLatest && (
            <TextControl
              label="ID du film"
              value={filmId || ""}
              type="number"
              onChange={(value) => setAttributes({ filmId: parseInt(value) })}
            />
          )}
        </InspectorControls>
        <div>
          {displayLatest
            ? "Affichage des 3 derniers films"
            : `Affichage du film avec ID : ${filmId}`}
        </div>
      </>
    );
  },
  save: () => {
    // Le rendu sera côté serveur avec PHP
    return null;
  },
});
