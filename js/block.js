const { registerBlockType } = wp.blocks;
const { TextControl, CheckboxControl } = wp.components;
const { InspectorControls } = wp.blockEditor;

registerBlockType("custom-plugin/film-block", {
  title: "Film Block",
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
              value={filmId}
              type="number"
              onChange={(value) => setAttributes({ filmId: parseInt(value) })}
            />
          )}
        </InspectorControls>
        <div>{displayLatest ? "Derniers films" : `Film ID: ${filmId}`}</div>
      </>
    );
  },
  save: () => {
    return null; // Les blocs dynamiques n'ont pas besoin de rendu côté client
  },
});
